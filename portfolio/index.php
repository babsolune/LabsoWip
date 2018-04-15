<?php
/*##################################################
 *                           index.php
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminPortfolioConfigController', '`^/admin(?:/config)?/?$`'),

	//Manage categories
	new UrlControllerMapper('PortfolioCategoriesManagerController', '`^/categories/?$`'),
	new UrlControllerMapper('PortfolioCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('PortfolioCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('PortfolioDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage items
	new UrlControllerMapper('PortfolioItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('PortfolioItemFormController', '`^/add/?([0-9]+)?/?$`', array('category_id')),
	new UrlControllerMapper('PortfolioItemFormController', '`^(?:/([0-9]+))/edit/?([0-9]+)?/?$`', array('id', 'page')),
	new UrlControllerMapper('PortfolioDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),

	//Display items
	new UrlControllerMapper('PortfolioDisplayTagController', '`^/tag(?:/([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page')),
	new UrlControllerMapper('PortfolioDisplayPendingItemsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	new UrlControllerMapper('PortfolioDisplayItemController', '`^(?:/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+))/?([0-9]+)?/?$`', array('category_id', 'rewrited_name_category', 'id', 'rewrited_title', 'page')),

	//Utilities
	new UrlControllerMapper('PortfolioPrintItemController', '`^/print/([0-9]+)-([a-z0-9-_]+)/?$`', array('id', 'rewrited_title')),

	//Display home and categories

	new UrlControllerMapper('PortfolioDownloadFileController', '`^/file/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('PortfolioVisitLinkController', '`^/visit/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('PortfolioDeadLinkController', '`^/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('PortfolioDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('category_id', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'))
);

DispatchManager::dispatch($url_controller_mappers);

?>
