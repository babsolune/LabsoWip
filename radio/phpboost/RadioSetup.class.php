<?php
/*##################################################
 *                             RadioSetup.class.php
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

class RadioSetup extends DefaultModuleSetup
{
	public static $radio_table;
	public static $radio_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$radio_table = PREFIX . 'radio';
		self::$radio_cats_table = PREFIX . 'radio_cats';
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
		ConfigManager::delete('radio', 'config');
		RadioService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$radio_table, self::$radio_cats_table));
	}

	private function create_tables()
	{
		$this->create_radio_table();
		$this->create_radio_cats_table();
	}

	private function create_radio_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'approbation_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'release_day' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'extra_list_enabled' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'name'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$radio_table, $fields, $options);
	}

	private function create_radio_cats_table()
	{
		RichCategory::create_categories_table(self::$radio_cats_table);
	}

	private function insert_data()
	{
        $this->messages = LangLoader::get('install', 'radio');
		$this->insert_radio_cats_data();
		$this->insert_radio_data();
	}

	private function insert_radio_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$radio_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['cat.name']),
			'name' => $this->messages['cat.name'],
			'description' => $this->messages['cat.description'],
			'image' => '/radio/radio.png'
		));
	}

	private function insert_radio_data()
	{
		PersistenceContext::get_querier()->insert(self::$radio_table, array(
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['radio.title'],
			'rewrited_name' => Url::encode_rewrite($this->messages['radio.title']),
			'contents' => $this->messages['radio.content'],
			'release_day' => $this->messages['radio.release.day'],
			'approbation_type' => Radio::APPROVAL_NOW,
			'start_date' => $this->messages['radio.start.date'],
			'end_date' => $this->messages['radio.end.date'],
			'extra_list_enabled' => 0,
			'picture_url' => '',
			'author_custom_name' => '',
			'author_user_id' => 1
		));
	}
}
?>
