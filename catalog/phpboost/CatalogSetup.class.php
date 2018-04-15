<?php
/*##################################################
 *                               CatalogSetup.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class CatalogSetup extends DefaultModuleSetup
{
	public static $catalog_table;
	public static $catalog_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$catalog_table = PREFIX . 'catalog';
		self::$catalog_cats_table = PREFIX . 'catalog_cats';
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
		ConfigManager::delete('catalog', 'config');
		CacheManager::invalidate('module', 'catalog');
		CatalogService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$catalog_table, self::$catalog_cats_table));
	}

	private function create_tables()
	{
		$this->create_catalog_table();
		$this->create_catalog_cats_table();
	}

	private function create_catalog_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'product_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'size' => array('type' => 'bigint', 'length' => 18, 'notnull' => 1, 'default' => 0),
			'contents' => array('type' => 'text', 'length' => 65000),
			'description' => array('type' => 'text', 'length' => 65000),
			'promotion_enabled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'promotion' => array('type' => 'decimal', 'length' => 18, 'scale' => 2),
			'price' => array('type' => 'decimal', 'notnull' => 1, 'length' => 18, 'scale' => 2),
			'shipping' => array('type' => 'decimal', 'notnull' => 1, 'length' => 18, 'scale' => 2),
			'approbation_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_view' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_downloads' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'flash_sales_enabled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'carousel' => array('type' => 'text', 'length' => 65000),
			'product_color' => array('type' => 'text', 'length' => 65000),
			'product_size' => array('type' => 'text', 'length' => 65000),
			'product_details' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'name'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents'),
				'description' => array('type' => 'fulltext', 'fields' => 'description')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$catalog_table, $fields, $options);
	}

	private function create_catalog_cats_table()
	{
		RichCategory::create_categories_table(self::$catalog_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'catalog');
		$this->insert_catalog_cats_data();
		$this->insert_catalog_data();
	}

	private function insert_catalog_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$catalog_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'image' => '/catalog/catalog.png'
		));
	}

	private function insert_catalog_data()
	{
		PersistenceContext::get_querier()->insert(self::$catalog_table, array(
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['default.product.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.product.name']),
			'product_url' => '/catalog/catalog.png',
			'size' => 1430,
			'contents' => $this->messages['default.product.content'],
			'description' => '',
			'promotion_enabled' => 0,
			'flash_sales_enabled' => 0,
			'approbation_type' => Product::APPROVAL_NOW,
			'start_date' => 0,
			'end_date' => 0,
			'creation_date' => time(),
			'updated_date' => time(),
			'author_custom_name' => '',
			'author_user_id' => 1,
			'number_downloads' => 0,
			'number_view' => 0,
			'picture_url' => '/catalog/catalog.png'
		));
	}
}
?>
