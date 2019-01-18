<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY [reidlos@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 3.0 - 2012 01 27
 * @contributor Julien BRISWALTER [j1.seth@phpboost.com]
 * @contributor Arnaud GENET [elenwii@phpboost.com]
 * @contributor mipel [mipel@phpboost.com]
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

namespace Wiki\phpboost;
use \Wiki\controllers\ItemsListCtrl;

class ModHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), ItemsListCtrl::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('module.title', 'common', 'wiki');
	}
}
?>
