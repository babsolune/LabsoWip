<?php
/*##################################################
 *		    TsmTreeLinks.class.php
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

class TsmTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$admin_lang = LangLoader::get('admin', 'tsm');
		$club_lang = LangLoader::get('club', 'tsm');
		$season_lang = LangLoader::get('season', 'tsm');
		$division_lang = LangLoader::get('division', 'tsm');
		$competition_lang = LangLoader::get('competition', 'tsm');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($admin_lang['admin.config'], TsmUrlBuilder::config()));

		// Seasons
		$tsm_season_links = new ModuleLink($season_lang['seasons.management'], TsmUrlBuilder::seasons_manager(), TsmUrlBuilder::seasons_manager(), TsmSeasonsAuthService::check_season_auth()->moderation_season());
		$tsm_season_links->add_sub_link(new ModuleLink($season_lang['seasons.management'], TsmUrlBuilder::seasons_manager(), TsmSeasonsAuthService::check_season_auth()->moderation_season()));
		$tsm_season_links->add_sub_link(new ModuleLink($season_lang['season.add'], TsmUrlBuilder::add_season(), TsmSeasonsAuthService::check_season_auth()->moderation_season()));
		$tree->add_link($tsm_season_links);

		// Divisions
		$tsm_division_links = new ModuleLink($division_lang['divisions.management'], TsmUrlBuilder::divisions_manager(), TsmUrlBuilder::divisions_manager(), TsmDivisionsAuthService::check_division_auth()->moderation_division());
		$tsm_division_links->add_sub_link(new ModuleLink($division_lang['divisions.management'], TsmUrlBuilder::divisions_manager(), TsmDivisionsAuthService::check_division_auth()->moderation_division()));
		$tsm_division_links->add_sub_link(new ModuleLink($division_lang['division.add'], TsmUrlBuilder::add_division(), TsmDivisionsAuthService::check_division_auth()->moderation_division()));
		$tree->add_link($tsm_division_links);

		// Clubs
		if (!TsmClubsAuthService::check_club_auth()->moderation_club())
		{
			$tree->add_link(new ModuleLink($club_lang['clubs.list'], TsmUrlBuilder::home_club()));
		}
		else
		{
			$tsm_club_links = new ModuleLink($club_lang['clubs.management'], TsmUrlBuilder::clubs_manager(), TsmUrlBuilder::clubs_manager(), TsmClubsAuthService::check_club_auth()->moderation_club());
			$tsm_club_links->add_sub_link(new ModuleLink($club_lang['clubs.list'], TsmUrlBuilder::home_club(), TsmClubsAuthService::check_club_auth()->moderation_club()));
			$tsm_club_links->add_sub_link(new ModuleLink($club_lang['clubs.management'], TsmUrlBuilder::clubs_manager(), TsmClubsAuthService::check_club_auth()->moderation_club()));
			$tsm_club_links->add_sub_link(new AdminModuleLink($club_lang['clubs.config'], TsmUrlBuilder::clubs_config(), TsmClubsAuthService::check_club_auth()->moderation_club()));
			$tsm_club_links->add_sub_link(new ModuleLink($club_lang['club.add'], TsmUrlBuilder::add_club(), TsmClubsAuthService::check_club_auth()->moderation_club()));
			$tree->add_link($tsm_club_links);
		}

		// Competitions
		$tsm_competition_links = new ModuleLink($competition_lang['competitions.management'], TsmUrlBuilder::competitions_manager(), TsmUrlBuilder::competitions_manager(), TsmCompetitionsAuthService::check_competition_auth()->moderation_competition());
		$tsm_competition_links->add_sub_link(new ModuleLink($competition_lang['competitions.management'], TsmUrlBuilder::competitions_manager(), TsmCompetitionsAuthService::check_competition_auth()->moderation_competition()));
		$tsm_competition_links->add_sub_link(new ModuleLink($competition_lang['competition.add'], TsmUrlBuilder::add_competition(), TsmCompetitionsAuthService::check_competition_auth()->moderation_competition()));
		$tree->add_link($tsm_competition_links);

		$tree->add_link(new AdminModuleLink($admin_lang['admin.competitions.manager'], TsmUrlBuilder::compet_manager()));
		$tree->add_link(new AdminModuleLink($admin_lang['admin.results.manager'], TsmUrlBuilder::results_manager()));

		return $tree;
	}
}
?>
