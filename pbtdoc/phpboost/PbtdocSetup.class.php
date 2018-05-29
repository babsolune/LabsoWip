<?php
/*##################################################
 *                             PbtdocSetup.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class PbtdocSetup extends DefaultModuleSetup
{
	public static $pbtdoc_table;
	public static $pbtdoc_cats_table;

	/**
	 * @var string[string] localized messages
	*/
	private $messages;

	public static function __static()
	{
		self::$pbtdoc_table = PREFIX . 'pbtdoc';
		self::$pbtdoc_cats_table = PREFIX . 'pbtdoc_cats';
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
		ConfigManager::delete('pbtdoc', 'config');
		PbtdocService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$pbtdoc_table, self::$pbtdoc_cats_table));
	}

	private function create_tables()
	{
		$this->create_pbtdoc_table();
		$this->create_pbtdoc_cats_table();
	}

	private function create_pbtdoc_table()
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
		PersistenceContext::get_dbms_utils()->create_table(self::$pbtdoc_table, $fields, $options);
	}

	private function create_pbtdoc_cats_table()
	{
		RichCategory::create_categories_table(self::$pbtdoc_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'pbtdoc');
		$this->insert_pbtdoc_cats_data();
		$this->insert_pbtdoc_data();
	}

	private function insert_pbtdoc_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$pbtdoc_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => '/pbtdoc/pbtdoc.png'
		));
	}

	private function insert_pbtdoc_data()
	{
		PersistenceContext::get_querier()->insert(self::$pbtdoc_table, array(
			'id' => 1,
			'id_category' => 1,
			'order_id' => 1,
			'thumbnail_url' => '/pbtdoc/templates/images/default.png',
			'title' => $this->messages['default.course.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.course.title']),
			'description' => $this->messages['default.course.description'],
			'contents' => $this->messages['default.course.contents'],
			'number_view' => 0,
			'author_user_id' => 1,
			'author_custom_name' => '',
			'author_name_displayed' => Course::AUTHOR_NAME_DISPLAYED,
			'published' => Course::PUBLISHED_NOW,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'date_created' => time(),
			'date_updated' => 0,
			'sources' => TextHelper::serialize(array())
		));
	}
}
?>
