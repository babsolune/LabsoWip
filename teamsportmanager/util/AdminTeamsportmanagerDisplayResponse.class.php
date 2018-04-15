<?php
/*##################################################
 *                     AdminTeamsportmanagerDisplayResponse.class.php
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

class AdminTeamsportmanagerDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'teamsportmanager');
		$admin_lang = LangLoader::get('admin', 'teamsportmanager');
		$this->set_title($lang['tsm.module.title']);

		$this->add_link($admin_lang['admin.config'], TeamsportmanagerUrlBuilder::config());
		$this->add_link($admin_lang['admin.competitions.manager'], TeamsportmanagerUrlBuilder::compet_manager());
		$this->add_link($admin_lang['admin.results.manager'], TeamsportmanagerUrlBuilder::results_manager());

		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('teamsportmanager')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['tsm.module.title']);
	}
}
?>
