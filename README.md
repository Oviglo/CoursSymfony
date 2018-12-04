# CoursSymfony

## Installation

### Nouveau projet
Commande pour créer un nouveau
```
composer create-project symfony\website-skeleton NomDuProjet
```

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
Encore est un bundle Sf pour féciliter l'installation de Webpack

Pour installer les modules 
```
npm install --save-dev
```

Pour générer les fichier CSS et JS entrez la commande
```
npm run dev
```

Pour générer les fichier en production
```
npm run build
```

Pour observer les changements en dev
```
npm run watch
``` 

## Dossiers

**assets:** Fichier js/scss

**bin:** Fichiers binaires tel que la console

**config:** Fichiers de configuration des modules (Sf < 4: un seul fichier config.yml)

**public:** Contient l'index.php et les fichiers statiques créés par WebPack

**src:** Tout le code source de l'application

**templates:** Contient toutes les vues (fichiers Twig) (Sf < 4: les vues sont dans le dossier Resource/Views des Bundle)

**tests:** Fichiers pour les tests unitaires

**translations:** Fichiers de traduction 

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

Paramètres par défaut
```php
/**
 * @Route("/{page}", name="index", requirements={"page": "\d+"}, defaults={"page" = 1})
 * /
```
Si "page" n'est pas renseignée, elle sera à 1

Définir les méthodes autorisées
```php 
/**
 * @Route("/new", methods={"GET", "POST"})
 * /
```

## Entity
Une entité est un objet qui représente une table de la base de données

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

Il y a d'autres événements tel que ```preRemove``` ou ```preUpdate```

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
 * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="articles")
 * @var ?\Doctrine\Common\Collections\ArrayCollection
 */
private $categories;
```

Relation inverse dans l'entité Category
```php
/**
 * @ORM\ManyToMany(targetEntity="App\Entity\Articles", mappedBy="categories)
 */
private $articles;
```

### Relation ManyToMany avec paramètres
Pour faire une relation ManyToMany avec paramètres il faut créer une entité intermédiaire
```php
// Panier
/**
 * @ORM\OneToMany(targetEntity="PanierProduit", mappedBy="panier")
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
 * @ORM\ManyToOne(targetEntity="PanierProduit", mappedBy="produit")
 */
```

## Formulaire
Pour afficher un formulaire on utilise une classe "FormType"

### Champs
L'ajout de champ se fait dans le méthode buildForm() en utilisant la méthode "add" de l'objet FormBuilderInterface

```php
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('title')
        ->add('content')
    ;
}
```

Sf définit automatiquement le type de champ en fonction des annotations ORM de l'entité 

```php
// use Symfony\Component\Form\Extension\Core\Type;
$builder->add('status', Type\ChoiceType::class, array(
    'label' => 'article.status',
    'choices' => array(
        'status.public' => 'public',
        'status.private' => 'private',
        'status.draft' => 'draft',
    ),
    'expanded' => true, // Affiche le champ sous forme de radio
    'multiple' => false, // Choix multiple ou non ()
    'required' => true, // champ requis ou non 
) ) // type string dans la db
```

Pour créer un champ qui n'est pas dans l'entité, utiliser l'option ```'mapped' => false```

### Query builder
Lorsqu'un champ affiche une liste d'entités (par exemple la liste des catégories), il est possible de modifier la requête faite par Sf

```php
->add('categories', null, array( // Type\EntityType::class
    'label' => 'article.categories',
    'expanded' => true,
    'query_builder' => function(EntityRepository $er) {
        return $er->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            // ->where('c.status = "public"')
        ;
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

### Formulaires imbriqués
Exemple: inclure le formulaire d'ajout d'image dans le formulaire d'article

```php
// use App\Form\ImageType;
$builder->add('image', ImageType::class)
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

Test si une variable n'est pas vide
```twig
{% if var is not empty %}{% endif %}
```

Test si une variable existe
```twig
{% if var is defined %}{% endif %}
```

Pour les tests booléens : ```and|or```
#### Boucles
```twig
{% for entity in entities %}{% endfor %}
```

Si la boucle est vide
```twig
{% for user in users %}
    <li>{{ user }}</li>
{% else %}
    <p>Aucun utilisateur</p>
{% endfor %}
```

Dans une boucle, la variable "loop" permet d'obtenir des informations sur la boucle:
```twig
{{ loop.index }} {# index de la boucle #}
{{ loop.revindex }} {# total - index #}
{{ loop.first }} {# booléen si on est dans la première itération #}
{{ loop.last }} {# booléen si on est dans la dernière itération #}
{{ loop.length }} 
```

Boucle for simple
```twig
{% for i in 1..10 %}
{% endfor %}

{% for a in 'a'..'z' %}
{% endfor %}
``` 

### Les filtres

Les filtres permettent de modifier une variable

```twig
{{ var|lower }}
{{ var|upper }}
{{ var|date('d/m/Y') }}
{{ var|raw }} {# Affiche au format HTML #}
{{ var|nl2br }}
```

### Fonctions
```twig
{% include 'template.html.twig', {var: 'var'} %} {# Inclus un template #}
{{ dump(article) }}
```

### Traduction
```twig
{{ 'article.count'|trans }}
{{ 'article.message'|trans({'%title%': entity.title}) }}
{{ 'article.count|transchoice(count, {'%count%': count})}}
```

```yaml
article:
    message: Article avec le titre %title%
    count: "{0} Aucun article|{1} 1 article|]1,Inf[ %count% articles" 
```

### Block
Les blocks permettent de redéfinir une partie d'une vue

```twig
{# parent.html.twig #}
<div class="partie-1">
{% block partie1 %}
{% endblock %}
</div>
```

```twig
{# child.html.twig #}
{% extends 'parent.html.twig' %}

{% block partie1 %}
{{ parent() }} {# affiche le contenu du block "partie1" de la vue parente #}
...
{% endblock %}
```

### Générer l'url d'une page de l'application
```twig
{{ path('nom_de_la_route', {'param1': 1}) }}
```

### Inclure un controller
```twig
{{ render(controller('App\\Controller\\ArticleController::recentArticles', { 'count' : 10 })) }}
```

### Formulaire
Afficher un formulaire complet
```twig
{{ form(form) }}
```

Décomposer les champs
```twig
{{ form_start(form) }} {# Balise <form> #}
    {{ form_errors(form) }} {# Erreurs globales #}

    {{ form_row(form.champ) }}
 
{{ form_end(form) }}
```


### Création d'extension
Il est possible d'ajouter des fonctions et des filtres à Twig en créant une extension

```php
// src/Twig

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('nom', [$this, 'nomDeLaMethode'])
        ];
    }
}
```
### Test de connexion
```twig
{% if is_granted('IS_AUTHENTICATED_REMEMBERED') %}
 {{ app.user.username }}
{% endif %}
```

## Repository
Un repository contient les requêtes vers la base de données

```php

public function findPublishedByUser(User $user)
{
    $qb = $this->createQueryBuilder('a')
        ->andWhere('a.pusblished = :published')
        ->andWhere('a.user = :user')
        ->setParameter('published', true)
        ->setParameter('user', $user) // Objet User
        ->getQuery()
    ;

    // Retourne un tableau d'objets Article
    return $qb->getResult(); // $qb->execute();
}
```

L'objet Paginator permet de gérer plus facilement la pagination
```php
public function findByPage($page = 1, $count = 10)
{
    $offset = ($page - 1) * $count; // Calcul offset de la requête

    $queryBuilder = $this->createQueryBuilder('a')
        ->select('a, u, c') // Ajoute l'utilisateur et les catégories pour optimiser le nombre de requêtes
        ->leftJoin('a.user', 'u')
        ->leftJoin('a.categories', 'c')
        ->setFirstResult($offset) // OFFSET
        ->setMaxResults($count) // LIMIT
        ->orderBy('a.id', 'DESC')
    ;

    return new Paginator($queryBuilder); // Liste + nombre total
}
```

Retourner un seul objet
```php
public function findLastByUser($user)
{
    $qb = $this->createQueryBuilder('a')
        ->andWhere('a.user = :user')
        ->setParameter('user', $user) // Objet User
        ->orderBy('a.id', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
    ;

    return $qb->getOneOrNullResult(); // 1 objet Article ou null
}
```

## Controller
Controller permet de traiter la requête client et retourne une réponse

```php
// Sf < 4.2
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Définit un prefix pour le nom et le chemin des routes de ce controller
 * @Route('admin/article', name="article_admin_")
 * 
 * Autorise seulement les membre avec ces rôles
 * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_MODERATOR')")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request, ArticleRepository $articleRepository /* SF 4 */) : Response // Sf < 4 => indexAction
    {
        // SF < 4
        /*
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)->findAll();
        */

        // SF 4
        $articles = $articleRepository->findAll();

        // Retourne une réponse avec le rendu de la vue twig
        return $this->render('admin/article/index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, TranslatorInterface $translator /* SF 4 */) : Response
    {
        $entity = new Article;
        $entity->setUser($this->getUser()); // Récupére l'utilisateur connecté
        // Création du formulaire à partir de la classe ArticleType
        $form = $this->createForm(ArticleType::class, $entity);
        // Envois la requête au formulaire
        $form->handleRequest($request);

        // Tester si le formulaire est envoyé et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity); // PDO -> prepare
            $em->flush(); // PDO -> execute

            // SF < 4.2
            //$translator = $this->get('translator');

            $this->addFlash('success', $translator->trans('article.new.success'));

            return $this->redirectToRoute('article_admin_index');
        }

        return $this->render('admin/article/new.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
```

### Requêtes AJAX

Test si la requête est en AJAX
```php
if ($request->isXmlHttpRequest()) {

    return $this->json(array(
        'var' => $var,
    ));
} 
```

## Services

Pour trouver la classe utilisée dans un service:
```
php bin/console debug:container security.token_storage
```

## Menu
La gestion des menus se fait avec KNPMenuBundle

Pour ajouter un nouveau menu, créer la class MenuBuilder

```php
// src/Form/AdminMenuBuilder.php
public function createMenu()
{
    $menu = $this->factory->createItem('root');

    // Ajouter un item
    $parent = $menu->addChild('chaine.a.traduire', [
        'route' => 'nom_de_la_route', 
        'routeParameters' => ['id' => 1 ],
        'attributes' => ['class' => 'test']
    ]);

    // ajouter un sous-menu, on ajoute des item au menu parent
    $parent->addChild('sous-item');

    // Item avec lien externe
    $menu->addChild('page.facebook', ['uri' => 'http://www.facebook.com']);
}
```

## Make Bundle
Aide à ecrire les classes

Créer une entité
```
php bin/console make:entity
```

```make:form```
```make:controller```

### Crud
Pour générer un CRUD admin propre:
- Faire une copie de l'entité dans le dossier Entity\Admin
- Faire la commande ```php bin/console make:crud``` et donner le lien vers l'entité du dossier admin ```Admin\User```
- Une fois le crud généré, supprimer le fichier dans Entity\Admin
- Faire un trouver/remplacer de "App\Entity\Admin\User" par "App\Entity\User"
- Faire un trouver/replacer dans les vues de "admin_user/" par "admin/user/"

## Bundles utiles
- Atlantic18/DoctrineExtensions

## Erreurs courantes
- Case mismatch between loaded and declared class names: "App\Controller\ArticleController" vs "App\controller\ArticleController".

Problème d'espace de nom ou de nom de classe

- The file "C:\xampp\htdocs\PHPOO\ProjetSymfony\config/services.yaml" does not contain valid YAML in 
Problème de validation YAML

- The file was found but the class was not in it, the class name or namespace probably has a typo.
Soit le nom de la classe ne correspond pas au nom du fichier, soit l'espace de nom n'est pas bon

- Object of class App\Entity\Category could not be converted to string
Il manque la méthode __toString dans l'objet

- Unable to generate a URL for the named route "admin_article_add" as such route does not exist.
Le nom de la route (dans un {{ path() }} par exemple) n'existe pas, faire la commande debug:router pour afficher les routes

- Unable to find template "admin/article/list.html.twig"
Soit le nom dans la méthode "render" du controller n'est pas bon, soit le fichier n'a pas été créé

-Controller "App\Controller\Admin\ArticleController::edit()" requires that you provide a value for the "$entity" argument. Either the argument is nullable and no null value has been provided, no default value has been provided or because there is a non optional argument after this one.

Oublis du typage d'un argument de méthode dans le controller (Article $entity)
