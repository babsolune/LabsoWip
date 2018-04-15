<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : September 13, 2017
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
	new UrlControllerMapper('AdminSponsorsConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminSponsorsDeleteParameterController', '`^/admin/delete/([a-z]+)/([0-9]+)/?$`', array('parameter', 'id')),

	//Categories
	new UrlControllerMapper('SponsorsCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('SponsorsCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('SponsorsCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('SponsorsDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('SponsorsManagePartnersController', '`^/manage/?$`'),
	new UrlControllerMapper('SponsorsFormPartnerController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('SponsorsFormPartnerController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('SponsorsDeletePartnerController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('SponsorsDisplayPartnerController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	//Keywords
	new UrlControllerMapper('SponsorsDisplayPartnersTagController', '`^/tag/([a-z0-9-_]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page')),

	new UrlControllerMapper('SponsorsDisplayPendingPartnersController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),

	new UrlControllerMapper('SponsorsVisitPartnerController', '`^/visit/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('SponsorsDeadLinkController', '`^/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('SponsorsDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
