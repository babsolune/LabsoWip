<?php
/*##################################################
 *		                         common.php
 *                            -------------------
 *   begin                : February 20, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 #                     French                       #
 ####################################################

$lang['module_config_title'] = 'Configuration de Palmarès';

$lang['palmares'] = 'Palmarès';
$lang['palmares.add'] = 'Ajouter un événement';
$lang['palmares.edit'] = 'Modifier un événement';
$lang['palmares.pending'] = 'Événement en attente';
$lang['palmares.manage'] = 'Gérer les événements';
$lang['palmares.management'] = 'Gestion des événements';

$lang['palmares.seo.description.root'] = 'Tous les événements du site :site.';
$lang['palmares.seo.description.tag'] = 'Tous les événements sur le sujet :subject.';
$lang['palmares.seo.description.pending'] = 'Tous les événements en attente.';

$lang['palmares.view'] = 'vues';

$lang['palmares.form.short_contents'] = 'Condensé de l\'événement';
$lang['palmares.form.short_contents.description'] = 'Pour que le condensé de l\'événement soit affiché, veuillez activer l\'option dans la configuration du module';
$lang['palmares.form.short_contents.enabled'] = 'Personnaliser le condensé de l\'événement';
$lang['palmares.form.short_contents.enabled.description'] = 'Si non coché, l\'événement est automatiquement coupé à :number caractères et le formatage du texte supprimé.';
$lang['palmares.form.top_list'] = 'Placer l\'événement en tête de liste';
$lang['palmares.form.contribution.explain'] = 'Vous n\'êtes pas autorisé à créer un événement, cependant vous pouvez en proposer un.';

//Administration
$lang['admin.config.number_columns_display_palmares'] = 'Nombre de colonnes pour afficher les palmarès';
$lang['admin.config.display_condensed'] = 'Afficher le condensé de l\'événement et non l\'événement entier';
$lang['admin.config.display_descriptions_to_guests'] = 'Afficher le condensé des événements aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';
$lang['admin.config.number_character_to_cut'] = 'Nombre de caractères pour couper l\'événement';
$lang['admin.config.palmares_suggestions_enabled'] = 'Activer le affichage des suggestions';
$lang['admin.config.palmares_number_view_enabled'] = 'Activer le affichage du nombre de vues';

//Feed name
$lang['feed.name'] = 'Palmarès';

//Form
$lang['palmares.form.author_custom_name_enabled'] = 'Personnaliser le nom du auteur';
$lang['palmares.form.author_custom_name'] = 'Nom du auteur';

//Messages
$lang['palmares.message.success.add'] = 'L\'événement <b>:name</b> a été ajouté';
$lang['palmares.message.success.edit'] = 'L\'événement <b>:name</b> a été modifié';
$lang['palmares.message.success.delete'] = 'L\'événement <b>:name</b> a été supprimé';
?>
