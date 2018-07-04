<?php
/*##################################################
 *                           index.php
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminWikiConfigController', '`^/admin(?:/config)?/?$`'),

	//Manage categories
	new UrlControllerMapper('WikiCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('WikiCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('WikiCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('WikiDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage Items
	new UrlControllerMapper('WikiManageItemsController', '`^/manage/?$`'),
	new UrlControllerMapper('WikiFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('WikiFormController', '`^(?:/([0-9]+))/edit/?([0-9]+)?/?$`', array('id', 'page')),
	new UrlControllerMapper('WikiDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('WikiReorderCategoryItemsController', '`^/reorder/([0-9]+)-?([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name')),

	//Display wiki
	new UrlControllerMapper('WikiDisplayItemsTagController', '`^/tag(?:/([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'page')),
	new UrlControllerMapper('WikiDisplayItemHistoryController', '`^(?:/([0-9]+))/history/?([0-9]+)?/?$`', array('history', 'page')),
	new UrlControllerMapper('WikiDisplayPendingItemsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('page')),
	new UrlControllerMapper('WikiDisplayItemController', '`^(?:/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+))/?([0-9]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_title', 'page')),

	//Utilities
	new UrlControllerMapper('WikiPrintItemController', '`^/print/([0-9]+)-([a-z0-9-_]+)/?$`', array('id', 'rewrited_title')),

	//Display home and categories
	new UrlControllerMapper('WikiDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page', 'subcategories_page'))
);

DispatchManager::dispatch($url_controller_mappers);

?>
