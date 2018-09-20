<?php
/*##################################################
 *                               StaffSetup.class.php
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

class StaffSetup extends DefaultModuleSetup
{
	public static $staff_table;
	public static $staff_cats_table;
	public static $staff_config_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$staff_table = PREFIX . 'staff';
		self::$staff_cats_table = PREFIX . 'staff_cats';
		self::$staff_config_table = PREFIX . 'staff_config';
	}

	public function upgrade($installed_version)
	{
		return '5.1.0';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('staff', 'config');
		CacheManager::invalidate('module', 'staff');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$staff_table, self::$staff_cats_table, self::$staff_config_table));
	}

	private function create_tables()
	{
		$this->create_staff_table();
		$this->create_staff_cats_table();
		$this->create_staff_config_table();
	}

	private function create_staff_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'order_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'lastname' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'firstname' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'role' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'item_phone' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'item_email' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
            'publication' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'group_leader' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'order_id' => array('type' => 'key', 'fields' => 'order_id'),
				'title' => array('type' => 'fulltext', 'fields' => 'lastname'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$staff_table, $fields, $options);
	}

	private function create_staff_config_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'roles' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$staff_config_table, $fields, $options);
	}

	private function create_staff_cats_table()
	{
		RichCategory::create_categories_table(self::$staff_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'staff');
		$this->insert_staff_config_data();
		$this->insert_staff_cats_data();
		$this->insert_staff_data();
	}

	private function insert_staff_config_data()
	{
		PersistenceContext::get_querier()->insert(self::$staff_config_table, array(
			'id' => 1,
			'roles' => TextHelper::serialize(array($this->messages['default.role'], 'Administrateur'))
		));
	}

	private function insert_staff_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$staff_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'image' => '/staff/templates/images/president.png'
		));
	}

	private function insert_staff_data()
	{
		PersistenceContext::get_querier()->insert(self::$staff_table, array(
			'id' => 1,
			'id_category' => 1,
			'order_id' => 1,
			'lastname' => $this->messages['default.adherent.lastname'],
			'firstname' => $this->messages['default.adherent.firstname'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.adherent.lastname'] . '-' . $this->messages['default.adherent.firstname']),
			'contents' => $this->messages['default.adherent.content'],
			'role' => $this->messages['default.role'],
			'item_phone' => $this->messages['default.adherent.phone'],
			'item_email' => $this->messages['default.adherent.email'],
            'thumbnail_url' => '/staff/templates/images/no_avatar.png',
			'publication' => 1,
			'creation_date' => time(),
			'author_user_id' => 1,
			'group_leader' => 1
		));
	}
}
?>
