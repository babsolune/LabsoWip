<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : May, 02, 2017
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminRadioConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('RadioCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('RadioCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('RadioCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('RadioDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage Radio
	new UrlControllerMapper('RadioManageController', '`^/manage/?$`'),
	new UrlControllerMapper('RadioFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('RadioFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('RadioDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('RadioDisplayRadioTagController', '`^/tag/([a-z0-9-_]+)?/?([0-9]+)?/?$`', array('tag', 'page')),
	new UrlControllerMapper('RadioDisplayPendingRadioController', '`^/pending/([0-9]+)?/?$`', array('page')),

	new UrlControllerMapper('RadioDisplayRadioController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	new UrlControllerMapper('RadioDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page')),

	new UrlControllerMapper('RadioDocumentationController', '`^/documentation/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
