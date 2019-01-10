<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

class OptionsConfig
{
	private $roles;

	public function add_role($role)
	{
		$this->roles[] = $role;
	}

	public function set_roles($roles)
	{
		$this->roles = $roles;
	}

	public function get_roles()
	{
		return $this->roles;
	}

	public function get_properties()
	{
		return array(
			'roles' => TextHelper::serialize($this->get_roles()),
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_roles(!empty($properties['roles']) ? TextHelper::unserialize($properties['roles']) : array());
	}

	public function init_default_properties()
	{
		$this->roles = array();
	}
}
?>
