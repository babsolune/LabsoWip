<?php
/*##################################################
 *		                AdminPalmaresDisplayResponse.class.php
 *                            -------------------
 *   begin                : April 13, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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
 * @author Sebastien Lartigue <babso@web33.fr>
 */

class AdminPalmaresDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'palmares');
		$this->set_title($lang['palmares']);

		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), PalmaresUrlBuilder::configuration());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('palmares')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['palmares']);
	}
}
?>
