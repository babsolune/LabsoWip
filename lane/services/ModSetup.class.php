<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 08 27
 * @since   	PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER [j1.seth@phpboost.com]
 * @contributor Arnaud GENET [elenwii@phpboost.com]
 * @contributor mipel [mipel@phpboost.com]
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

namespace Wiki\services;
use \Wiki\services\ModCategory;
use \Wiki\services\ModItem;
use \Wiki\services\ModServices;

class ModSetup extends DefaultModuleSetup
{
	public static $items_table;
	public static $categories_table;
	public static $favorite_items_table;

	/**
	 * @var string[string] localized messages
	*/
	private $messages;

	public static function __static()
	{
		self::$items_table          = PREFIX . 'wiki';
		self::$categories_table     = PREFIX . 'wiki_cats';
		self::$favorite_items_table = PREFIX . 'wiki_favorites';
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
		ModServices::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$items_table, self::$categories_table, self::$favorite_items_table));
	}

	private function create_tables()
	{
		$this->create_items_table();
		$this->create_categories_table();
		$this->create_favorite_items_table();
	}

	private function create_items_table()
	{
		$fields = array(
			'id'                    => array('type' => 'integer', 'length'  => 11, 'autoincrement' => true, 'notnull' => 1),
			'category_id'           => array('type' => 'integer', 'length'  => 11, 'notnull'       => 1, 'default'    => 0),
			'order_id'              => array('type' => 'integer', 'length'  => 11, 'notnull'       => 1),
			'thumbnail_url'         => array('type' => 'string', 'length'   => 255, 'notnull'      => 1, 'default'    => "''"),
			'title'                 => array('type' => 'string', 'length'   => 250, 'notnull'      => 1, 'default'    => "''"),
			'rewrited_title'        => array('type' => 'string', 'length'   => 250, 'default'      => "''"),
			'description'           => array('type' => 'text', 'length'     => 65000),
			'contents'              => array('type' => 'text', 'length'     => 65000),
			'number_view'           => array('type' => 'integer', 'length'  => 11, 'default'       => 0),
			'author_custom_name'    => array('type' => 'string', 'length'   => 255, 'default'      => "''"),
			'author_user_id'        => array('type' => 'integer', 'length'  => 11, 'notnull'       => 1, 'default'    => 0),
			'author_name_displayed' => array('type' => 'boolean', 'notnull' => 1, 'default'        => 1),
			'published'             => array('type' => 'integer', 'length'  => 1, 'notnull'        => 1, 'default'    => 0),
			'publishing_start_date' => array('type' => 'integer', 'length'  => 11, 'notnull'       => 1, 'default'    => 0),
			'publishing_end_date'   => array('type' => 'integer', 'length'  => 11, 'notnull'       => 1, 'default'    => 0),
			'date_created'          => array('type' => 'integer', 'length'  => 11, 'notnull'       => 1, 'default'    => 0),
			'date_updated'          => array('type' => 'integer', 'length'  => 11, 'notnull'       => 1, 'default'    => 0),
			'sources'               => array('type' => 'text', 'length'     => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'category_id' => array('type' => 'key', 'fields'      => 'category_id'),
				'order_id'    => array('type' => 'key', 'fields'      => 'order_id'),
				'title'       => array('type' => 'fulltext', 'fields' => 'title'),
				'description' => array('type' => 'fulltext', 'fields' => 'description'),
				'contents'    => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$items_table, $fields, $options);
	}

	private function create_categories_table()
	{
		ModCategory::create_categories_table(self::$categories_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'wiki');
		$this->insert_default_category_data();
		$this->insert_default_item_data();
	}

	private function insert_default_category_data()
	{
		PersistenceContext::get_querier()->insert(self::$categories_table, array(
			'id'            => 1,
			'id_parent'     => 0,
			'c_order'       => 1,
			'auth'          => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name'          => $this->messages['default.category.name'],
			'description'   => $this->messages['default.category.description'],
			'image'         => '/wiki/templates/images/default.jpg'
		));
	}

	private function create_favorite_items_table()
	{
		$fields = array(
			'id'      => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull'       => 1, 'default'    => 0),
			'item_id' => array('type' => 'integer', 'length' => 11, 'notnull'       => 1, 'default'    => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$favorite_items_table, $fields, $options);
	}

	private function insert_default_item_data()
	{
		PersistenceContext::get_querier()->insert(self::$items_table, array(
			'id'                    => 1,
			'category_id'           => 1,
			'order_id'              => 1,
			'thumbnail_url'         => '/wiki/templates/images/default.jpg',
			'title'                 => $this->messages['default.item.title'],
			'rewrited_title'        => Url::encode_rewrite($this->messages['default.item.title']),
			'description'           => $this->messages['default.item.description'],
			'contents'              => $this->messages['default.item.contents'],
			'number_view'           => 0,
			'author_user_id'        => 1,
			'author_custom_name'    => '',
			'author_name_displayed' => ModItem::AUTHOR_NAME_DISPLAYED,
			'published'             => ModItem::PUBLISHED_NOW,
			'publishing_start_date' => 0,
			'publishing_end_date'   => 0,
			'date_created'          => time(),
			'date_updated'          => 0,
			'sources'               => TextHelper::serialize(array())
		));
	}
}
?>
