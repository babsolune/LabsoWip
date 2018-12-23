<?php

/**
 * @package 	SingleMenu
 * @subpackage 	PHPBoost
 * @category 	Modules
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 04 21
 * @since   	PHPBoost 5.0 - 2016 04 21
 */

class SingleMenuModuleMiniMenu extends ModuleMiniMenu
{
	private $config;
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
		return 'module-mini-single-menu';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('sgm.module.title', 'common', 'SingleMenu');
	}

	public function is_displayed()
	{
		return true;
	}

	public function get_menu_content()
	{
		$user = AppContext::get_current_user();
		$this->config = SingleMenuConfig::load();

		$tpl = new FileTemplate('SingleMenu/SingleMenuModuleMiniMenu.tpl');
		$tpl->add_lang(LangLoader::get('common', 'SingleMenu'));
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		$tpl->put_all(array(
			'C_MENU_LEFT'       => $this->get_block() == Menu::BLOCK_POSITION__LEFT,
			'C_MENU_RIGHT'      => $this->get_block() == Menu::BLOCK_POSITION__RIGHT,
			'C_OPEN_NEW_WINDOW' => $this->config->is_new_window(),
			'MENU_TITLE'        => $this->config->get_menu_title()
		));

		$links = $this->config->get_link_data();

		$i = 1;
		foreach ($links as $id => $options)
		{
			if(filter_var($options['link_url'], FILTER_VALIDATE_URL))
				$external_link = true;
			else
				$external_link = false;

			$tpl->assign_block_vars('links', array(
				'C_LINK_NAME'     => !empty($options['link_name']),
				'C_FA_LINK'       => !empty($options['fa_link']),
				'C_IMG_LINK'      => !empty($options['img_link']),
				'C_EXTERNAL_LINK' => $external_link,

				'LINK_URL'        => Url::to_rel($options['link_url']),
				'LINK_NAME'       => $options['link_name'],
				'FA_LINK'         => $options['fa_link'],
				'IMG_LINK'        => Url::to_rel($options['img_link'])
			));
			$i++;
		}

		return $tpl->render();
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			return $this->get_menu_content();
		}

		return '';
	}
}
?>
