<?php
/*##################################################
 *                               OptionsConfig.class.php
 *                            -------------------
 *   begin                : June 29, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
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
