<?php
/*##################################################
 *                               common.php
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
 #						English						#
 ####################################################

$lang['module_title'] = 'Catalogs';
$lang['module_config_title'] = 'Catalogs configuration';

$lang['catalog.actions.add'] = 'Add product';
$lang['catalog.add'] = 'New product';
$lang['catalog.edit'] = 'File edition';
$lang['catalog.pending'] = 'Pending products';
$lang['catalog.manage'] = 'Manage products';
$lang['catalog.management'] = 'Files management';

$lang['most_downloaded_products'] = 'Most downloaded products';
$lang['last_catalog_products'] = 'Last catalog products';
$lang['catalog'] = 'Catalog';
$lang['downloaded_times'] = 'Downloaded :number_downloads times';
$lang['downloads_number'] = 'Downloads number';
$lang['product_infos'] = 'File informations';
$lang['product'] = 'File';
$lang['products'] = 'Files';

$lang['product.view'] = 'views';
$lang['product.number.view'] = 'Number of views';

//config
$lang['config.category_display_type'] = 'Displayed informations in categories';
$lang['config.category_display_type.display_summary'] = 'Summary';
$lang['config.category_display_type.display_all_content'] = 'All content';
$lang['config.category_display_type.display_table'] = 'Table';
$lang['config.display_descriptions_to_guests'] = 'Display summary products to guests if they don\'t have read permission';
$lang['config.viewed_products_menu'] = 'Cataloged products menu';
$lang['config.sort_type'] = 'Files display order';
$lang['config.sort_type.explain'] = 'Descending mode';
$lang['config.products_number_in_menu'] = 'Max products displayed number';
$lang['config.limit_oldest_product_day_in_menu'] = 'Limit products age in menu';
$lang['config.oldest_product_day_in_menu'] = 'Maximum age (in days)';
$lang['admin.config.catalog_number_view_enabled'] = 'Enable number of view display';

//authorizations
$lang['authorizations.display_product'] = 'Display catalog link permission';

//SEO
$lang['catalog.seo.description.tag'] = 'All catalogs on :subject.';
$lang['catalog.seo.description.pending'] = 'All pending catalogs.';

//contribution
$lang['catalog.form.contribution.explain'] = 'You are not authorized to post a new product, however you can contribute by submitting one.';

//Form
$lang['catalog.form.reset_number_downloads'] = 'Reset catalogs number';
$lang['news.form.author_custom_name_enabled'] = 'Personalize author name';
$lang['news.form.author_custom_name'] = 'Author name';

//Messages
$lang['catalog.message.success.add'] = 'The product <b>:name</b> has been added';
$lang['catalog.message.success.edit'] = 'The product <b>:name</b> has been modified';
$lang['catalog.message.success.delete'] = 'The product <b>:name</b> has been deleted';
$lang['catalog.message.error.product_not_found'] = 'File not found, the link may be dead.';
$lang['catalog.message.warning.unauthorized_to_catalog_product'] = 'You are not authorized to catalog the product.';
?>
