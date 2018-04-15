<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : June 23, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminClubsConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('ClubsCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('ClubsCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('ClubsCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ClubsDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('ClubsManageController', '`^/manage/?$`'),
	new UrlControllerMapper('ClubsFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('ClubsFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ClubsDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('ClubsDisplayClubController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	new UrlControllerMapper('ClubsDisplayPendingClubsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),

	new UrlControllerMapper('ClubsVisitClubController', '`^/visit/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('ClubsDeadLinkController', '`^/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('ClubsDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
