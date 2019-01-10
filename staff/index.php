<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminStaffConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('StaffCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('StaffCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('StaffCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('StaffDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),


	//Management
	new UrlControllerMapper('StaffManageController', '`^/manage/?$`'),
	new UrlControllerMapper('StaffItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('StaffItemFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('StaffDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('StaffDisplayItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),
	new UrlControllerMapper('StaffReorderCategoryItemsController', '`^/reorder/([0-9]+)-?([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name')),

	new UrlControllerMapper('StaffDisplayPendingItemsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('page')),

	// Home
	new UrlControllerMapper('StaffDisplayHomeController', '`^/?$`'),
	new UrlControllerMapper('StaffDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page', 'subcategories_page')),

);
DispatchManager::dispatch($url_controller_mappers);
?>
