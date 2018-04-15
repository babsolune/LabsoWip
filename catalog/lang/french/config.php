<?php
/*##################################################
 *                               config.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #			French			    #
 ####################################################

$lang['module_config_title'] = 'Configuration du catalogue';
$lang['config.title.module'] = 'Configuration du module';
$lang['config.title.home'] = 'Configuration de la page d\'accueil du module';
$lang['config.title.menu'] = 'Configuration du mini module';

$lang['root_category_description'] = 'Bienvenue sur le catalogue de nos produits.
<br /><br />
Une catégorie et un produit ont été créés pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> Pour configurer ou personnaliser l\'accueil de votre module, rendez vous dans l\'<a href="' . CatalogUrlBuilder::configuration()->relative() . '">administration du module</a></li>
	<li class="formatter-li"> Pour créer des catégories, <a href="' . CatalogUrlBuilder::add_category()->relative() . '">cliquez ici</a> </li>
	<li class="formatter-li"> Pour ajouter des produits, <a href="' . CatalogUrlBuilder::add()->relative() . '">cliquez ici</a></li>
</ul>
<br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="http://www.phpboost.com">PHPBoost</a>.';

$lang['config.flash.sales.enabled'] = 'Afficher les ventes flash';
$lang['config.last.products.enabled'] = 'Afficher les derniers produits ajoutés';
$lang['config.last.promoted.products.enabled'] = 'Afficher les derniers produits en promotion';
$lang['config.display.nb.products'] = 'Nombre de produits à afficher';
$lang['config.display.nb.products.desc'] = 'De 1 à 10 max';

$lang['downloads_number'] = 'Nombre de téléchargements';
$lang['product.number.view'] = 'Nombre de vues';

$lang['config.price.unit'] = 'Devise';
$lang['config.category_display_type'] = 'Affichage des informations dans les catégories';
$lang['config.category_display_type.display_summary'] = 'Résumé';
$lang['config.category_display_type.display_all_content'] = 'Tout le contenu';
$lang['config.category_display_type.display_table'] = 'Tableau';
$lang['config.display_descriptions_to_guests'] = 'Afficher le résumé des produits aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';
$lang['config.sort_type'] = 'Ordre d\'affichage des produits';
$lang['config.sort_type.explain'] = 'Sens décroissant';
$lang['config.products_number_in_menu'] = 'Nombre de produits affichés maximum';
$lang['config.limit_oldest_product_day_in_menu'] = 'Limiter l\'âge des produits dans le menu';
$lang['config.oldest_product_day_in_menu'] = 'Age maximum (en jours)';
$lang['admin.config.catalog_number_view_enabled'] = 'Activer l\'affichage du nombre de vues';

$lang['config.price.unit.1'] = '€';
$lang['config.price.unit.2'] = '$';
$lang['config.price.unit.3'] = '£';

//authorizations
$lang['authorizations.display_product'] = 'Autorisation d\'afficher le lien de téléchargement';
?>
