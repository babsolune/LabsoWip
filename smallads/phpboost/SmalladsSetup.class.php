<?php
/*##################################################
 *                             SmalladsSetup.class.php
 *                            -------------------
 *   begin                : March 15, 2018
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

class SmalladsSetup extends DefaultModuleSetup
{
	public static $smallads_table;
	public static $smallads_cats_table;

	/**
	 * @var string[string] localized messages
	*/
	private $messages;

	public static function __static()
	{
		self::$smallads_table = PREFIX . 'smallads';
		self::$smallads_cats_table = PREFIX . 'smallads_cats';
	}

	public function upgrade($installed_version)
	{
		return 'alpha 0.0.3';
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
		ConfigManager::delete('smallads', 'config');
		SmalladsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$smallads_table, self::$smallads_cats_table));
	}

	private function create_tables()
	{
		$this->create_smallads_table();
		$this->create_smallads_cats_table();
	}

	private function create_smallads_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 65000),
			'price' => array('type' => 'decimal', 'notnull' => 1, 'length' => 18, 'scale' => 2, 'default' => 0),
			'max_weeks' => array('type' => 'integer', 'notnull' => 1, 'length' => 11, 'default' => 0),
			'smallad_type' => array('type' => 'string', 'length' => 255),
			'brand' => array('type' => 'string', 'length' => 255),
			'sold' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'location' => array('type' => 'text', 'length' => 65000),

			'views_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'displayed_author_email' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'custom_author_email' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'displayed_author_pm' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'displayed_author_name' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'custom_author_name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'displayed_author_phone' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'author_phone' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'publication_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publication_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),

			'sources' => array('type' => 'text', 'length' => 65000),
			'carousel' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'description' => array('type' => 'fulltext', 'fields' => 'description'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$smallads_table, $fields, $options);
	}

	private function create_smallads_cats_table()
	{
		RichCategory::create_categories_table(self::$smallads_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'smallads');
		$this->insert_smallads_cats_data();
		$this->insert_smallads_data();
	}

	private function insert_smallads_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$smallads_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => '/smallads/smallads.png'
		));
	}

	private function insert_smallads_data()
	{
		PersistenceContext::get_querier()->insert(self::$smallads_table, array(
			'id' => 1,
			'id_category' => 1,
			'thumbnail_url' => '/smallads/templates/images/default.png',
			'title' => $this->messages['default.smallad.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.smallad.title']),
			'description' => $this->messages['default.smallad.description'],
			'contents' => $this->messages['default.smallad.contents'],
			'views_number' => 0,
			'max_weeks' => 1,
			'author_user_id' => 1,
			'custom_author_name' => '',
			'displayed_author_name' => Smallad::DISPLAYED_AUTHOR_NAME,
			'displayed_author_email' => Smallad::NOTDISPLAYED_AUTHOR_EMAIL,
			'displayed_author_pm' => Smallad::NOTDISPLAYED_AUTHOR_PM,
			'displayed_author_phone' => Smallad::NOTDISPLAYED_AUTHOR_PHONE,
			'published' => Smallad::PUBLISHED_NOW,
			'publication_start_date' => 0,
			'publication_end_date' => 0,
			'creation_date' => time(),
			'updated_date' => 0,
			'smallad_type' => $this->messages['default.smallad.type'],
			'sources' => TextHelper::serialize(array()),
			'carousel' => TextHelper::serialize(array())
		));
	}
}
?>
