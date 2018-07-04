<?php
/*##################################################
 *                        common.php
 *                            -------------------
 *   begin                : May 25, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
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

//Titles
$lang['module.title'] = 'Wiki';
$lang['documents'] = 'Articles';
$lang['document'] = 'Article';
$lang['module_config_title'] = 'Configuration du wiki';
$lang['wiki_management'] = 'Gestion des articles';
$lang['wiki.add'] = 'Ajouter un article';
$lang['wiki.edit'] = 'Modification d\'un article';
$lang['wiki.feed_name'] = 'Derniers articles';
$lang['wiki.pending_documents'] = 'Articles en attente';
$lang['wiki.published_wiki'] = 'Articles publiés';
$lang['wiki.select_page'] = 'Sélectionnez une page';
$lang['wiki.summary'] = 'Sommaire :';
$lang['wiki.print.document'] = 'Impression d\'un article';
$lang['wiki.views.nb'] = 'Nombres de vues';
$lang['wiki.creation.date'] = 'Date de création';
$lang['wiki.updated.date'] = 'Dernière mise à jour';
$lang['wiki.historic'] = 'Historique des modifications';

$lang['wiki.sub.categories'] = 'Sous-catégories';
$lang['wiki.documents.nb'] = 'Nombre d\'articles';
$lang['wiki.reorder'] = 'Réordonner les articles';
$lang['wiki.document.pages'] = 'Les pages de l\'article';


//Articles configuration
$lang['wiki_configuration.display_icon_cats'] = 'Afficher l\'icône des catégories';
$lang['wiki_configuration.display_color_cats'] = 'Afficher la couleur des catégories';
$lang['wiki_configuration.number_character_to_cut'] = 'Nombre de caractères pour couper le condensé de l\'article';
$lang['wiki_configuration.display_type'] = 'Type d\'affichage';
$lang['wiki_configuration.display_type.mosaic'] = 'Mosaïque';
$lang['wiki_configuration.display_type.list'] = 'Liste';
$lang['wiki_configuration.display_type.table'] = 'Tableau';
$lang['wiki_configuration.display_type.block'] = 'Liste sans image';
$lang['wiki_configuration.display_descriptions_to_guests'] = 'Afficher le condensé des articles aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';

//Form
$lang['wiki.category.color'] = 'Couleur de la catégorie';

$lang['wiki.form.description'] = 'Description (maximum :number caractères)';
$lang['wiki.form.description_enabled'] = 'Activer le condensé de l\'article';
$lang['wiki.form.description_enabled.description'] = 'ou laissez PHPBoost couper le contenu à :number caractères';
$lang['wiki.form.add_page'] = 'Insérer une page';
$lang['wiki.form.add_page.title'] = 'Titre de la nouvelle page';
$lang['wiki.form.thumbnail'] = 'Vignette de l\'article';
$lang['wiki.form.member.edition'] = 'Modification par un membre';
$lang['wiki.form.member.edition.explain'] = 'Vous êtes sur le point de modifier cette annonce. Elle va être déplacée dans les annonces en attente afin d\'être traitée et une nouvelle alerte sera envoyée à un administrateur.';
$lang['wiki.form.member.edition.description'] = 'Complément de modification';
$lang['wiki.form.member.edition.description.desc'] = 'Expliquez ce que vous avez modifié pour un meilleur traitement d\'approbation.';

//SEO
$lang['wiki.seo.description.root'] = 'Tous les articles du site :site.';
$lang['wiki.seo.description.tag'] = 'Tous les articles sur le sujet :subject.';
$lang['wiki.seo.description.pending'] = 'Tous les articles en attente.';

//Messages
$lang['wiki.message.success.add'] = 'L\'article <b>:title</b> a été ajouté';
$lang['wiki.message.success.edit'] = 'L\'article <b>:title</b> a été modifié';
$lang['wiki.message.success.delete'] = 'L\'article <b>:title</b> a été supprimé';
?>
