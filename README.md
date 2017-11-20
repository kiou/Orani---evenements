## Administration

* Cr�ation d'un �v�nement
* Gestion d'un �v�nement
* Modifier un �v�nement
* Publication d'un �v�nement
* Ajouter une cat�gorie
* Gestion des cat�gories
* Modifier une cat�gorie



## Client
* Liste des �v�nements
* Trier par cat�gorie
* Page d'un �v�nement
* Template mise en avant des �v�nement
* Template affichage en calendrier


## D�pendances
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

<a href="#" data-nav="evenement-menu" class="menuNav {{ getCurrentMenu(menuEvenement) }}"> <i class="fa fa-newspaper-o"></i> Ev�nements <i class="fa fa-angle-right"></i></a>
<ul id="evenement-menu" class="{{ getCurrentMenu(menuEvenement) }}">
    <li class="{{ getCurrentMenu(['admin_evenement_ajouter']) }}"><a href="{{ path('admin_evenement_ajouter')}}">Ajouter un �v�nement</a></li>
    <li class="{{ getCurrentMenu(['admin_evenement_manager']) }}"><a href="{{ path('admin_evenement_manager')}}">Gestion des �v�nements</a></li>
    <li class="{{ getCurrentMenu(['admin_evenementcategorie_manager']) }}"><a href="{{ path('admin_evenementcategorie_manager')}}">Gestion des cat�gories</a></li>
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