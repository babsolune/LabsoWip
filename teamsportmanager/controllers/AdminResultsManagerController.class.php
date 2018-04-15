<?php
/*##################################################
 *                   AdminResultsManagerController.class.php
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

class AdminResultsManagerController extends AdminModuleController
{
	private $admin_lang,
			$tpl;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		return new AdminTeamsportmanagerDisplayResponse($this->tpl, $this->admin_lang['admin.results.manager']);
	}

	private function init()
	{
		$this->tpl = new FileTemplate('teamsportmanager/AdminResultsManagerController.tpl');
		$this->admin_lang = LangLoader::get('admin', 'teamsportmanager');
		$this->tpl->add_lang($this->admin_lang);
	}
}
?>
