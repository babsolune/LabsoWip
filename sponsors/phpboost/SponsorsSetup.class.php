<?php
/*##################################################
 *                             SponsorsSetup.class.php
 *                            -------------------
 *   begin                : May 20, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
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

class SponsorsSetup extends DefaultModuleSetup
{
	public static $sponsors_table;
	public static $sponsors_cats_table;

	/**
	 * @var string[string] localized messages
	*/
	private $messages;

	public static function __static()
	{
		self::$sponsors_table = PREFIX . 'sponsors';
		self::$sponsors_cats_table = PREFIX . 'sponsors_cats';
	}

	public function upgrade($installed_version)
	{
		return '5.1.2';
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
		ConfigManager::delete('sponsors', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$sponsors_table, self::$sponsors_cats_table));
	}

	private function create_tables()
	{
		$this->create_sponsors_table();
		$this->create_sponsors_cats_table();
	}

	private function create_sponsors_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'website_url' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'partner_level' => array('type' => 'string', 'length' => 255),
			'contents' => array('type' => 'text', 'length' => 65000),
			'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'views_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'visits_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'publication_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publication_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$sponsors_table, $fields, $options);
	}

	private function create_sponsors_cats_table()
	{
		RichCategory::create_categories_table(self::$sponsors_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'sponsors');
		$this->insert_sponsors_data();
		$this->insert_sponsors_cats_data();
	}

	private function insert_sponsors_cats_data()
	{
		$this->messages = LangLoader::get('install', 'sponsors');
		PersistenceContext::get_querier()->insert(self::$sponsors_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => '/sponsors/sponsors.png'
		));
	}

	private function insert_sponsors_data()
	{
		PersistenceContext::get_querier()->insert(self::$sponsors_table, array(
			'id' => 1,
			'id_category' => 1,
			'title' => $this->messages['default.partner.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.partner.title']),
			'website_url' => 'https://www.phpboost.com',
			'partner_level' => 1,
			'contents' => $this->messages['default.partner.contents'],
			'thumbnail_url' => '/sponsors/templates/images/default.png',
			'views_number' => 0,
			'visits_number' => 0,
			'author_user_id' => 1,
			'published' => Partner::PUBLISHED_NOW,
			'publication_start_date' => 0,
			'publication_end_date' => 0,
			'creation_date' => time(),
			'updated_date' => 0,
		));
	}
}
?>
