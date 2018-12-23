<?php

/**
 * @package 	Newscat
 * @subpackage 	Util
 * @category 	Modules
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 01 01
 * @since   	PHPBoost 5.2 - 2018 11 27
 */

class NewscatUrlBuilder
{
	private static $dispatcher = '/newscat';

	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
}
?>