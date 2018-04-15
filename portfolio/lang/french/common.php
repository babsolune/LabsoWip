<?php
/*##################################################
 *                        common.php
 *                            -------------------
 *   begin                : November 29, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

 ####################################################
 #                      French					    #
 ####################################################

// Titles
$lang['portfolio.module.title'] = 'Portfolio';
$lang['portfolio.item'] = 'Présentation';
$lang['portfolio.items'] = 'Présentations';
$lang['module.config.title'] = 'Configuration des présentations';
$lang['portfolio.management'] = 'Gestion des présentations';
$lang['portfolio.add'] = 'Ajouter une présentation';
$lang['portfolio.edit'] = 'Modification d\'une présentation';
$lang['portfolio.feed.name'] = 'Dernières présentations';
$lang['portfolio.pending.items'] = 'Présentations en attente';
$lang['portfolio.published.items'] = 'Présentations publiées';
$lang['portfolio.print.item'] = 'Impression d\'une présentation';
$lang['portfolio.summary'] = 'Sommaire';
$lang['portfolio.visit'] = 'Visiter le site';
$lang['portfolio.visits.number'] = 'Nombre de visites';
$lang['portfolio.file'] = 'Télécharger le fichier';
$lang['portfolio.downloads.number'] = 'Nombre de téléchargements';
$lang['portfolio.external.file'] = 'Site externe';
$lang['portfolio.dead.link'] = 'Déclarer un lien mort';

//Portfolio configuration
$lang['portfolio.configuration.cats.icon.display'] = 'Afficher l\'icône des catégories';
$lang['portfolio.configuration.sort.filter.display'] = 'Afficher les filtres de tri';
$lang['portfolio.configuration.suggestions.display'] = 'Afficher les suggestions de présentations';
$lang['portfolio.configuration.suggestions.nb'] = 'Nombre de présentations suggérées à afficher';
$lang['portfolio.configuration.navigation.links.display'] = 'Afficher la navigation des présentations connexes';
$lang['portfolio.configuration.navigation.links.display.desc'] = 'Lien précédent, lien suivant';
$lang['portfolio.configuration.characters.number.to.cut'] = 'Nombre de caractères pour couper le condensé de la présentation';
$lang['portfolio.configuration.display.type'] = 'Type d\'affichage des présentations';
$lang['portfolio.configuration.mosaic.type.display'] = 'Mosaïque';
$lang['portfolio.configuration.list.type.display'] = 'Liste';
$lang['portfolio.configuration.table.type.display'] = 'Tableau';
$lang['portfolio.configuration.display.descriptions.to.guests'] = 'Afficher le condensé des présentations aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';

//Form
$lang['portfolio.form.description'] = 'Description (maximum :number caractères)';
$lang['portfolio.form.enabled.description'] = 'Activer le condensé de la présentation';
$lang['portfolio.form.enabled.description.description'] = 'ou laissez PHPBoost couper le contenu à :number caractères';
$lang['portfolio.form.carousel'] = 'Ajouter un carousel d\'images';
$lang['portfolio.form.image.description'] = 'Description';
$lang['portfolio.form.add.page'] = 'Insérer une page';
$lang['portfolio.form.add.page.title'] = 'Titre de la nouvelle page';
$lang['portfolio.form.image.url'] = 'Adresse image';
$lang['portfolio.form.enabled.author.name.customisation'] = 'Personnaliser le nom de l\'auteur';
$lang['portfolio.form.enable.links.visibility'] = 'Permettre aux visiteurs de voir les liens';
$lang['portfolio.form.custom.author.name'] = 'Nom de l\'auteur personnalisé';
$lang['portfolio.form.website.url'] = 'Adresse du site';
$lang['portfolio.form.file.url'] = 'Adresse du fichier à télécharger';
$lang['portfolio.form.reset.downloads.number'] = 'Remettre le compteur de téléchargement à 0';

//Sort fields title and mode
$lang['portfolio.sort.field.views'] = 'Vues';
$lang['admin.portfolio.sort.field.published'] = 'Publié';

//SEO
$lang['portfolio.seo.description.root'] = 'Toutes les présentations du site :site.';
$lang['portfolio.seo.description.tag'] = 'Toutes les présentations sur le sujet :subject.';
$lang['portfolio.seo.description.pending'] = 'Toutes les présentations en attente.';

//Messages
$lang['portfolio.message.success.add'] = 'La présentation <b>:title</b> a été ajoutée';
$lang['portfolio.message.success.edit'] = 'La présentation <b>:title</b> a été modifiée';
$lang['portfolio.message.success.delete'] = 'La présentation <b>:title</b> a été supprimée';
?>
