<?php
/**
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2018 11 27
 * @since       PHPBoost 5.1 - 2018 11 27
*/

class NewscatSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '5.2.0';
	}

	public function uninstall()
	{
		ConfigManager::delete('network-links', 'config');
	}
}
?>
