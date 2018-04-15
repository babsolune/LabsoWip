<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Categories
	new UrlControllerMapper('AgendaCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('AgendaCategoriesFormController', '`^/categories/add/?$`'),
	new UrlControllerMapper('AgendaCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('AgendaDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Admin
	new UrlControllerMapper('AdminAgendaConfigController', '`^/admin(?:/config)?/?$`'),

	//Display events
	new UrlControllerMapper('AgendaDisplayEventController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	//Manage events
	new UrlControllerMapper('AgendaManageEventsController', '`^/manage/?$`'),
	new UrlControllerMapper('AgendaFormController', '`^/add/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month', 'day', 'id_category')),
	new UrlControllerMapper('AgendaFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('AgendaDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('AgendaSuscribeController', '`^/([0-9]+)/suscribe/?$`', array('event_id')),
	new UrlControllerMapper('AgendaUnsuscribeController', '`^/([0-9]+)/unsuscribe/?$`', array('event_id')),

	new UrlControllerMapper('AgendaDisplayPendingEventsController', '`^/pending/?$`'),
	new UrlControllerMapper('AgendaEventsListController', '`^/events_list/?$`'),

	// Documentation
	new UrlControllerMapper('AgendaDocumentationController', '`^/documentation/?$`'),

	//Display agenda
	new UrlControllerMapper('AgendaAjaxAgendaController', '`^/ajax_month_agenda/([0-9]+)?/?([0-9]+)?/?(\d{0,1})?/?$`', array('agenda_ajax_year', 'agenda_ajax_month', 'agenda_mini')),
	new UrlControllerMapper('AgendaAjaxEventsController', '`^/ajax_month_events/([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('agenda_ajax_year', 'agenda_ajax_month', 'agenda_ajax_day')),
	new UrlControllerMapper('AgendaDisplayCategoryController', '`^/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('year', 'month', 'day')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
