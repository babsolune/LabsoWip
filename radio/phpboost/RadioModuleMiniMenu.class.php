<?php
/*##################################################
 *                        RadioModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class RadioModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_menu_id()
	{
		return 'module-mini-radio';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('radio', 'common', 'radio');
	}

	public function is_displayed()
	{
		return RadioAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
    	if (RadioAuthorizationsService::check_authorizations()->read())
		{

			$lang = LangLoader::get('common', 'radio');
			$tpl = new FileTemplate('radio/RadioModuleMiniMenu.tpl');
			$tpl->add_lang($lang);
			MenuService::assign_positions_conditions($tpl, $this->get_block());

			$radio_config = RadioConfig::load();

			$tpl->put_all(array(
				'C_POPUP' => $radio_config->is_radio_popup(),
				'C_AUTOPLAY' => $radio_config->is_radio_autoplay(),
				'C_IMG' => !empty($radio_config->get_radio_img()),

				'L_RADIO_NAME' => $radio_config->get_radio_name(),

				'U_RADIO_PLAYER' => RadioUrlBuilder::home()->rel(),
				'U_RADIO_IMG' => $radio_config->get_radio_img()->rel(),
				'U_NETWORK' => $radio_config->get_radio_url()->rel()
			));

			return $tpl->render();
		}
	}
}
?>
