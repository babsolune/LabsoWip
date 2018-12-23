<?php

/**
 * @package 	SingleMenu
 * @subpackage 	Util
 * @category 	Modules
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 04 21
 * @since   	PHPBoost 5.0 - 2016 04 21
 */

class SingleMenuUrlBuilder
{
	private static $dispatcher = '/SingleMenu';

	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
}
?>
