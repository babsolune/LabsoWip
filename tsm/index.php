<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : February 13, 2018
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
	//Admin
	new UrlControllerMapper('AdminTsmConfigController', '`^/admin(?:/config)?/?$`'),

	// Seasons
	new UrlControllerMapper('TsmSeasonsManagerController', '`^/seasons/manager/?$`'),
	new UrlControllerMapper('TsmSeasonsFormController', '`^/season/add/?([0-9]+)?/?$`'),
	new UrlControllerMapper('TsmSeasonsFormController', '`^/season/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('TsmSeasonsDeleteController', '`^/season/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('TsmDisplaySeasonController', '`^/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id', 'name')),

	// Divisions
	new UrlControllerMapper('TsmDivisionsManagerController', '`^/divisions/manager/?$`'),
	new UrlControllerMapper('TsmDivisionsFormController', '`^/division/add/?([0-9]+)?/?$`'),
	new UrlControllerMapper('TsmDivisionsFormController', '`^/division/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('TsmDivisionsDeleteController', '`^/division/([0-9]+)/delete/?$`', array('id')),

	// Clubs
	new UrlControllerMapper('AdminTsmClubsConfigController', '`^/admin/clubs/?$`'),
	new UrlControllerMapper('TsmClubsManagerController', '`^/clubs/manager/?$`'),
	new UrlControllerMapper('TsmClubsFormController', '`^/club/add/?([0-9]+)?/?$`'),
	new UrlControllerMapper('TsmClubsFormController', '`^/club/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('TsmClubsDeleteController', '`^/club/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('TsmClubsDisplayClubController', '`^/club/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id', 'rewrited_name')),
	new UrlControllerMapper('TsmClubsVisitWebsiteController', '`^/club/visit/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('TsmClubsDeadLinkController', '`^/club/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('TsmClubsDisplayAllController', '`^/clubs/?$`'),

	// Competitions
	new UrlControllerMapper('TsmCompetitionsManagerController', '`^/competitions/manager/?$`'),
	new UrlControllerMapper('TsmCompetitionsFormController', '`^/competition/add/?([0-9]+)?/?$`'),
	new UrlControllerMapper('TsmCompetitionsFormController', '`^/competition/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('TsmCompetitionsDeleteController', '`^/competition/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('TsmDisplayCompetitionController', '`^/([0-9]+)-([a-z0-9-_]+)?/?$`', array('season_id', 'season_name', 'division_id', 'division_rewrited_name')),

	// Home
	new UrlControllerMapper('TsmDisplayHomeController', '`^/?$`'),

);

DispatchManager::dispatch($url_controller_mappers);

?>
