<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 15
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesSetup extends DefaultModuleSetup
{
	public static $pages_table;
	public static $pages_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$pages_table = PREFIX . 'pages';
		self::$pages_cats_table = PREFIX . 'pages_cats';
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
		ConfigManager::delete('pages', 'config');
		CacheManager::invalidate('module', 'pages');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$pages_table, self::$pages_cats_table));
	}

	private function create_tables()
	{
		$this->create_pages_table();
		$this->create_pages_cats_table();
	}

	private function create_pages_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'i_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'content' => array('type' => 'text', 'length' => 65000),
			'author_display' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publication' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),

			'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'views_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'sources' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$pages_table, $fields, $options);
	}

	private function create_pages_cats_table()
	{
		RichCategory::create_categories_table(self::$pages_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'pages');
		$this->insert_pages_cats_data();
		$this->insert_pages_data();
	}

	private function insert_pages_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$pages_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'name' => $this->messages['default.cat.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'description' => $this->messages['default.cat.description'],
			'thumbnail' => '/templates/default/images/default_category_thumbnail.png'
		));
	}

	private function insert_pages_data()
	{
		PersistenceContext::get_querier()->insert(self::$pages_table, array(
			'id' => 1,
			'id_category' => 1,
			'i_order' => 1,
			'title' => $this->messages['default.page.name'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.page.name']),
			'content' => $this->messages['default.page.content'],
			'publication' => Page::APPROVAL_NOW,
			'start_date' => 0,
			'end_date' => 0,
			'creation_date' => time(),
			'updated_date' => time(),
			'author_display' => 1,
			'author_custom_name' => '',
			'author_user_id' => 1,

			'thumbnail_url' => '/templates/default/images/default_item_thumbnail.png',
			'views_number' => 0,
			'sources' => TextHelper::serialize(array()),
		));
	}
}
?>
