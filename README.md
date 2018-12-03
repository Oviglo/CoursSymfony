# CoursSymfony

## Installation
Pour installer les bibliothèques PHP externes entrez cette commande 
```
composer install
```

Configurez l'accés à la base de données dans le fichier .env
Ensuite mettre à jour la base de donnée avec la commande
```
php bin/console doctrine:schema:update --force
```

### Webpack
Webpack permet de condenser tous les fichiers assets dans un seul
Ex tous les fichier js sont minifiés et placés dans un seul fichier

Pour installer les modules 
```
npm install --save-dev
```

Pour générer les fichier CSS et JS entrez la commande
```
npm run dev
```

## Dossiers

**assets:** Fichier js/scss

**bin:** Fichiers binaires tel que la console

**config:** Fichiers de configuration des modules (Sf < 4: un seul fichier config.yml)

**public:** Contient l'index.php et les fichiers statiques créés par WebPack

**src:** Tout le code source de l'application

**templates:** Contient toutes les vues (fichiers Twig)

**tests:** Fichiers pour les tests unitaires

**translations:** Fichiers de traduction (Sf < 4: les vues sont dans le dossier Resource/Views des Bundle)

**var:** Contient le cache et les fichiers log

**vendor:** Bibliothèques externes

## Route
Afficher les routes
```
php bin/console debug:router
```

### Annotation
Les annotations sont des instructions définies dans un commentaire doc (/** */), elles permettent de définir des paramètres rapidement sans aller dans les fichiers config
Par exemple pour définir les routes dans un controller
```php
/**
 * @Route("/chemin/de/la/route")
 */
```
Avec paramètres
```php
/**
 * @Route("/edit/{id}", name="edit", requirements={"id":"\d+"})
 */
```
## Entity

### Annotations
Définir l'entité, annotation à mettre au dessus de la déclaration de classe
```php
/**
 * @ORM\Entity(repositoryClass="Namespace\De\La\Classe")
 * @ORM\Table(name="nom_de_la_table")
 */
```

Définir une colonne
```php
/**
 * @ORM\Column(name="nom_du_champ", type="string|text|integer|float|datetime|json_array", nullable=true, length=255)
 */
```

### Cycle de vie
Il est possible d'indiquer à Doctrine d'appeler des méthodes en fonction de ce qui est fait de l'entité
Par exemple appeler une méthode avant le persist
Il faut ajouter l'annotation ```@ORM\HasLifecycleCallbacks``` suivant en dessous de ```@ORM\entity```
```php
/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
```

Ainsi la méthode suivante est appeler avant un persist
```php
/**
 * @ORM\PrePersist()
 */
public function prePersist()
{
}
```

Il y a d'autres événement tel que ```preRemove``` ou ```preUpdate```

### Relations

Des annotations permettent de définir des relations entres objets

#### Relation OneToOne
Exemple: Une seule Image pour un seul Article
```php
/**
 * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"all"}, orphanRemoval=true)
 * @var ?\App\Entity\Image
 */
private $image;
```
*L'attribut "cascade" parmet d'enregistrer l'image en même temps que l'article (ainsi que la suppression)
*L'attribut "orphanRemoval" indique que si l'attribut est null, Doctrine doit supprimer l'image associée s'il y en a une

#### Relation ManyToOne
Plusieurs objets peuvent être associés à un seul autre
```php
/**
 * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
 */
```
Pour une relation inverse (ex: obtenir les articles d'une catégorie)

```php
/**
 * @ORM\OneToMany(targetEntity"Article", mappedBy="category")
 */
``` 

#### Relation ManyToMany
Exemple: plusieurs articles dans plusieurs catégories
Ainsi Doctrine va créer une table intermediaire qui sera complétement transparente dans notre appli

```php
/**
 * @ORM\ManyToMany(targetEntity="App\Entity\Category")
 * @var ?\Doctrine\Common\Collections\ArrayCollection
 */
private $categories;
```

### Relation ManyToMany avec paramètres
Pour faire une relation ManyToMany avec paramètres il faut créer une entité intermédiaire
```php
// Panier
/**
 * @ORM\OneToMany(targetEntity="PanierProduit")
 */
```
```php
// PanierProduit
/**
 * @ORM\ManyToOne(targetEntity="Panier", inversedBy="panierProduits")
 */

 /**
  * @ORM\ManyToOne(targetEntity="Produit", inversedBy="panierProduits")
  */
```
 ```php
// Produit
/**
 * @ORM\ManyToOne(targetEntity="PanierProduit")
 */
```

## Formulaire
Pour afficher un formulaire on utilise une classe "FormType" 

### Query builder
Lorsqu'un champ affiche une liste d'entités (par exemple la liste des catégories), il est possible de modifier la requête faite par Sf

```php
->add('categories', null, array(
    'label' => 'article.categories',
    'expanded' => true,
    'query_builder' => function(EntityRepository $er) {
        return $er->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC');
    } 
))
```

### Evénements

Il est possible de modifier le formulaire en fonction de l'entité (par exemple si l'article contient une image, afficher le champ pour la supprimer), on utilise dans ce cas les événements de formulaire dans la méthode BuildForm

```php
// Evénement pour ajouter ou non le champ "deleteImage" 
$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
    $entity = $event->getData(); // Entité envoyée au formulaire 
    $form = $event->getForm(); // Récupére le formulaire

    if (!is_null($entity->getImage())) { // S'il y a une image dans mon article
        // Ajout du champ "deleteImage" seulement s'il y a une image
        $form->add('deleteImage', Type\CheckboxType::class, array(
            'label' => 'image.delete',
            'required' => false, // Désactive le required
        ));
    }
});
```

## FOSUserBundle
Pour la gestion d'utilisateur, nous utilisont un bundle qui va nous proposer des formulaires de connexion, enregistrement par défaut

```
php bin/console fos:user:create --super-admin
```

## Twig
Twig est un moteur de template, il propose un langage simplifié pour créer nos vues

### Conditions et boucles
#### Conditions
```twig
{% if condition %} {% else %} {% endif %}
```

#### Boucles
```twig
{% for entity in entities %}{% endfor %}
```

Dans une boucle, la variable "loop" permet d'obtenir des informations sur la boucle:
```twig
{{ loop.index }} {# index de la boucle #}
{{ loop.first }} {# booléen si on est dans la première itération #}
{{ loop.last }} {# booléen si on est dans la dernière itération #}
```
### Générer l'url d'une page de l'application
```twig
{{ path('nom_de_la_route', {'param1': 1}) }}
```

### Inclure un controller
```twig
{{ render(controller('App\\Controller\\ArticleController::recentArticles', { 'count' : 10 })) }}
```

## Make Bundle

### Crud
Pour générer un CRUD admin propre:
- Faire une copie de l'entité dans le dossier Entity\Admin
- Faire la commande ```php bin/console make:crud``` et donner le lien vers l'entité du dossier admin ```Admin\User```
- Une fois le crud généré, supprimer le fichier dans Entity\Admin
- Faire un trouver/remplacer de "App\Entity\Admin\User" par "App\Entity\User"
- Faire un trouver/replacer dans les vues de "admin_user/" par "admin/user/"
