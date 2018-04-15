<?php
/*##################################################
 *                        NetworkLinksModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : reidlos@phpboost.com
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

class NetworkLinksModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-networklinks';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('nl.module.title', 'common', 'NetworkLinks');
	}

	public function is_displayed()
	{
		return true;
	}

	public function get_menu_content()
	{
		$user = AppContext::get_current_user();
		$config = NetworkLinksConfig::load();

		$tpl = new FileTemplate('NetworkLinks/NetworkLinksModuleMiniMenu.tpl');
		$tpl->add_lang(LangLoader::get('common', 'NetworkLinks'));
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		$tpl->put_all(array(
			'C_MENU_LEFT' => $this->get_block() == Menu::BLOCK_POSITION__LEFT,
			'C_MENU_RIGHT' => $this->get_block() == Menu::BLOCK_POSITION__RIGHT,
			'C_NEW_WINDOW' => $config->get_open_new_window()
		));

		$links = $config->get_link_data();

		$i = 1;
		foreach ($links as $id => $options)
		{
			$tpl->assign_block_vars('links', array(
				'C_LINK_NAME' => !empty($options['link_name']),
				'C_FA_LINK' => !empty($options['fa_link']),
				'C_IMG_LINK' => !empty($options['img_link']),

				'LINK_URL' => $options['link_url'],
				'LINK_NAME' => $options['link_name'],
				'FA_LINK' => $options['fa_link'],
				'IMG_LINK' => $options['img_link']
			));
			$i++;
		}

		return $tpl->render();
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			if ($this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT)
			{
				$template = $this->get_template_to_use();
				MenuService::assign_positions_conditions($template, $this->get_block());
				$this->assign_common_template_variables($template);

				$template->put_all(array(
					'ID' => $this->get_menu_id(),
					'TITLE' => $this->get_menu_title(),
					'CONTENTS' => $this->get_menu_content()
				));

				return $template->render();
			}
			else
			{
				return $this->get_menu_content();
			}
		}

		return '';
	}
}
?>
