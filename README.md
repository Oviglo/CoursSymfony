# CoursSymfony

## Installation
Pour installer les bibliothèques PHP externes entrez cette commande 
```
composer install
```

Pour installer les bibliothèques JS et CSS entrez cette commande
```
nom install
```

Configurez l'accés à la base de données dans le fichier .env
Ensuite mettre à jour la base de donnée avec la commande
```
php bin/console doctrine:schema:update --force
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

## Webpack
Webpack permet de condenser tous les fichiers assets dans un seul
Ex tous les fichier js sont minifiés et placés dans un seul fichier

Pour installer les modules 
```
npm install --save-dev
```

## Route
Afficher les routes
```php
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
 * @Route("/edit/{id}", requirements={"id":"\d+"})
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