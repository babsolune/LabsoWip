<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : April 13, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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
 * @author Sebastien Lartigue <babso@web33.fr>
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminPalmaresConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('PalmaresCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('PalmaresCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('PalmaresCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('PalmaresDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage Palmares
	new UrlControllerMapper('PalmaresManageController', '`^/manage/?$`'),
	new UrlControllerMapper('PalmaresFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('PalmaresFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('PalmaresDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('PalmaresDisplayPalmaresTagController', '`^/tag/([a-z0-9-_]+)?/?([0-9]+)?/?$`', array('tag', 'page')),
	new UrlControllerMapper('PalmaresDisplayPendingPalmaresController', '`^/pending/([0-9]+)?/?$`', array('page')),

	new UrlControllerMapper('PalmaresDisplayPalmaresController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	new UrlControllerMapper('PalmaresDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
