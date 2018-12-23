<?php
/*##################################################
 *                             WikiSetup.class.php
 *                            -------------------
 *   begin                : May 25, 2018
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

class WikiSetup extends DefaultModuleSetup
{
	public static $wiki_table;
	public static $wiki_cats_table;
	public static $wiki_favorites_table;

	/**
	 * @var string[string] localized messages
	*/
	private $messages;

	public static function __static()
	{
		self::$wiki_table = PREFIX . 'wiki';
		self::$wiki_cats_table = PREFIX . 'wiki_cats';
		self::$wiki_favorites_table = PREFIX . 'wiki_favorites';
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
		ConfigManager::delete('wiki', 'config');
		WikiService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$wiki_table, self::$wiki_cats_table, self::$wiki_favorites_table));
	}

	private function create_tables()
	{
		$this->create_wiki_table();
		$this->create_wiki_cats_table();
		$this->create_wiki_favorites_table();
	}

	private function create_wiki_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'order_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 65000),
			'number_view' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_name_displayed' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'publishing_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publishing_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'date_created' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'date_updated' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'sources' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'order_id' => array('type' => 'key', 'fields' => 'order_id'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'description' => array('type' => 'fulltext', 'fields' => 'description'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_table, $fields, $options);
	}

	private function create_wiki_cats_table()
	{
		WikiCategory::create_categories_table(self::$wiki_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'wiki');
		$this->insert_wiki_cats_data();
		$this->insert_wiki_data();
	}

	private function insert_wiki_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$wiki_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => '/wiki/wiki.png'
		));
	}

	private function create_wiki_favorites_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'document_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_favorites_table, $fields, $options);
	}

	private function insert_wiki_data()
	{
		PersistenceContext::get_querier()->insert(self::$wiki_table, array(
			'id' => 1,
			'id_category' => 1,
			'order_id' => 1,
			'thumbnail_url' => '/wiki/templates/images/default.jpg',
			'title' => $this->messages['default.document.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.document.title']),
			'description' => $this->messages['default.document.description'],
			'contents' => $this->messages['default.document.contents'],
			'number_view' => 0,
			'author_user_id' => 1,
			'author_custom_name' => '',
			'author_name_displayed' => Document::AUTHOR_NAME_DISPLAYED,
			'published' => Document::PUBLISHED_NOW,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'date_created' => time(),
			'date_updated' => 0,
			'sources' => TextHelper::serialize(array())
		));
	}
}
?>
