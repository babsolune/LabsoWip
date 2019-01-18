<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL [ben.popeye@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 1.6 - 2006 10 09
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminConfigCtrl', '`^/admin(?:/config)?/?$`'),

	//Manage categories
	new UrlControllerMapper('CategoriesManagerCtrl', '`^/categories/?$`'),
	new UrlControllerMapper('CategoryFormCtrl', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('CategoryFormCtrl', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('CategoryRemovalCtrl', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage Items
	new UrlControllerMapper('ItemsManagerCtrl', '`^/manage/?$`'),
	new UrlControllerMapper('ItemFormCtrl', '`^/add/?([0-9]+)?/?$`', array('category_id')),
	new UrlControllerMapper('ItemFormCtrl', '`^(?:/([0-9]+))/edit/?([0-9]+)?/?$`', array('id', 'page')),
	new UrlControllerMapper('ItemRemovalCtrl', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('ItemsOrganizerCtrl', '`^/reorder/([0-9]+)-?([a-z0-9-_]+)?/?$`', array('category_id', 'rewrited_name')),

	//Display Items
	new UrlControllerMapper('KeywordCtrl', '`^/keyword(?:/([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('keyword', 'page')),
	new UrlControllerMapper('FavoriteItemsCtrl', '`^/favorites(?:/([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('favorites', 'page')),
	new UrlControllerMapper('ItemHistoryCtrl', '`^(?:/([0-9]+))/history/?([0-9]+)?/?$`', array('history', 'page')),
	new UrlControllerMapper('PendingItemsCtrl', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('page')),
	new UrlControllerMapper('ItemCtrl', '`^(?:/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+))/?([0-9]+)?/?$`', array('category_id', 'rewrited_name_category', 'id', 'rewrited_title', 'page')),

	//Utilities
	new UrlControllerMapper('PrintItemCtrl', '`^/print/([0-9]+)-([a-z0-9-_]+)/?$`', array('id', 'rewrited_title')),

	//Display home and categories
	new UrlControllerMapper('ItemsListCtrl', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('category_id', 'rewrited_name', 'page', 'subcategories_page'))
);

DispatchManager::dispatch($url_controller_mappers);

?>
