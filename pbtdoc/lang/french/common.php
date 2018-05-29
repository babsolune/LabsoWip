<?php
/*##################################################
 *                        common.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

 ####################################################
 #                      French					    #
 ####################################################

//Titles
$lang['module.title'] = 'Documentation';
$lang['courses'] = 'Articles';
$lang['course'] = 'Article';
$lang['module_config_title'] = 'Configuration des articles';
$lang['pbtdoc_management'] = 'Gestion des articles';
$lang['pbtdoc.add'] = 'Ajouter un article';
$lang['pbtdoc.edit'] = 'Modification d\'un article';
$lang['pbtdoc.feed_name'] = 'Derniers pbtdoc';
$lang['pbtdoc.pending_courses'] = 'Articles en attente';
$lang['pbtdoc.published_pbtdoc'] = 'Articles publiés';
$lang['pbtdoc.select_page'] = 'Sélectionnez une page';
$lang['pbtdoc.summary'] = 'Sommaire :';
$lang['pbtdoc.print.course'] = 'Impression d\'un article';
$lang['pbtdoc.views.nb'] = 'Nombres de vues';
$lang['pbtdoc.creation.date'] = 'Date de création';
$lang['pbtdoc.updated.date'] = 'Dernière mise à jour';

$lang['pbtdoc.sub.categories'] = 'Sous-catégories';
$lang['pbtdoc.courses.nb'] = 'Nombre d\'articles';
$lang['pbtdoc.reorder'] = 'Réordonner les articles';
$lang['pbtdoc.course.pages'] = 'Les pages de l\'article';


//Articles configuration
$lang['pbtdoc_configuration.display_icon_cats'] = 'Afficher l\'icône des catégories';
$lang['pbtdoc_configuration.number_character_to_cut'] = 'Nombre de caractères pour couper le condensé de l\'article';
$lang['pbtdoc_configuration.display_type'] = 'Type d\'affichage des pbtdoc';
$lang['pbtdoc_configuration.display_type.mosaic'] = 'Mosaïque';
$lang['pbtdoc_configuration.display_type.list'] = 'Liste';
$lang['pbtdoc_configuration.display_type.table'] = 'Tableau';
$lang['pbtdoc_configuration.display_type.block'] = 'Liste sans image';
$lang['pbtdoc_configuration.display_descriptions_to_guests'] = 'Afficher le condensé des pbtdoc aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';

//Form
$lang['pbtdoc.form.description'] = 'Description (maximum :number caractères)';
$lang['pbtdoc.form.description_enabled'] = 'Activer le condensé de l\'article';
$lang['pbtdoc.form.description_enabled.description'] = 'ou laissez PHPBoost couper le contenu à :number caractères';
$lang['pbtdoc.form.add_page'] = 'Insérer une page';
$lang['pbtdoc.form.add_page.title'] = 'Titre de la nouvelle page';
$lang['pbtdoc.form.thumbnail'] = 'Vignette de l\'article';
$lang['pbtdoc.form.member.edition'] = 'Modification par un membre';
$lang['pbtdoc.form.member.edition.explain'] = 'Vous êtes sur le point de modifier cette annonce. Elle va être déplacée dans les annonces en attente afin d\'être traitée et une nouvelle alerte sera envoyée à un administrateur.';
$lang['pbtdoc.form.member.edition.description'] = 'Complément de modification';
$lang['pbtdoc.form.member.edition.description.desc'] = 'Expliquez ce que vous avez modifié pour un meilleur traitement d\'approbation.';

//SEO
$lang['pbtdoc.seo.description.root'] = 'Tous les pbtdoc du site :site.';
$lang['pbtdoc.seo.description.tag'] = 'Tous les pbtdoc sur le sujet :subject.';
$lang['pbtdoc.seo.description.pending'] = 'Tous les pbtdoc en attente.';

//Messages
$lang['pbtdoc.message.success.add'] = 'L\'article <b>:title</b> a été ajouté';
$lang['pbtdoc.message.success.edit'] = 'L\'article <b>:title</b> a été modifié';
$lang['pbtdoc.message.success.delete'] = 'L\'article <b>:title</b> a été supprimé';
?>
