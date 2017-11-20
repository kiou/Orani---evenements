## Administration

* Création d'un événement
* Gestion d'un événement
* Modifier un événement
* Publication d'un événement
* Ajouter une catégorie
* Gestion des catégories
* Modifier une catégorie



## Client
* Liste des événements
* Trier par catégorie
* Page d'un événement
* Template mise en avant des événement
* Template affichage en calendrier


## Dépendances
* ReferencementBundle
* GlobalBundle
* Tinymce
* DateDropper
* Filemanager
* SweetAlert

## Installation

### Menu
```twig
{% set menuEvenement = ['admin_evenement_manager', 'admin_evenement_ajouter', 'admin_evenement_modifier','admin_evenementcategorie_manager', 'admin_evenementcategorie_ajouter', 'admin_evenementcategorie_modifier'] %}

<a href="#" data-nav="evenement-menu" class="menuNav {{ getCurrentMenu(menuEvenement) }}"> <i class="fa fa-newspaper-o"></i> Evénements <i class="fa fa-angle-right"></i></a>
<ul id="evenement-menu" class="{{ getCurrentMenu(menuEvenement) }}">
    <li class="{{ getCurrentMenu(['admin_evenement_ajouter']) }}"><a href="{{ path('admin_evenement_ajouter')}}">Ajouter un événement</a></li>
    <li class="{{ getCurrentMenu(['admin_evenement_manager']) }}"><a href="{{ path('admin_evenement_manager')}}">Gestion des événements</a></li>
    <li class="{{ getCurrentMenu(['admin_evenementcategorie_manager']) }}"><a href="{{ path('admin_evenementcategorie_manager')}}">Gestion des catégories</a></li>
</ul>
```
### Fichier
* app/AppKernel.php
```php
new EvenementBundleEvenementBundle(),
```
* app/config.yml
```yml
- { resource: "@EvenementBundme/Resources/config/services.yml" }
```
* app/routing.yml
```yml
evenement:
    resource: "@EvenementBundle/Resources/config/routing.yml"
    prefix:   /
```
## Client
* Ajouter le dossier web/img/evenement/tmp
* Ajouter le dossier web/img/evenement/minitaure
* Design disponible dans le dossier Install