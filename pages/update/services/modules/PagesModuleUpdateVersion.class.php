<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 14
 * @since       PHPBoost 4.0 - 2014 05 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesModuleUpdateVersion extends ModuleUpdateVersion
{

	protected function execute_module_specific_changes()
	{
		$result = $this->querier->select('SELECT page.id, page.title, page.rewrited_title, page.auth, page.is_cat, page.content, cat.id as cat_id
			FROM ' . PREFIX . 'pages page
			LEFT JOIN ' . PREFIX . 'pages_cats cat ON cat.id_page = page.id
			WHERE page.is_cat = 1'
		);

		while ($row = $result->fetch())
		{
			// UPDATE category columns
			$this->querier->update(PREFIX . 'pages_cats', array('name' => $row['title'], 'rewrited_name' => $row['rewrited_title'], 'description' => $row['content'], 'auth' => $row['auth']), 'WHERE id=:id', array('id' => $row['cat_id']));
		}
		$result->dispose();

		//Delete old is_cat pages
		$this->querier->delete(PREFIX . 'pages', 'WHERE is_cat = 1');

		// update publication
		$this->querier->update(PREFIX . 'pages', array('publication' => 1), 'WHERE publication = 0');
		$this->querier->update(PREFIX . 'pages_cats', array('special_authorizations' => 1), 'WHERE auth != ""');
	}

	public function __construct()
	{
		parent::__construct('pages');

		$this->content_tables = array(PREFIX . 'pages');
		$this->delete_old_files_list = array(
			'/lang/english/pages_english.php',
			'/lang/french/pages_french.php',
			'/phpboost/PagesCategoriesCache.class.php',
			'/phpboost/PagesHomePageExtensionPoint.class.php',
			'/templates/action.tpl',
			'/templates/admin_pages.tpl',
			'/templates/com.tpl',
			'/templates/explorer.tpl',
			'/templates/index.tpl',
			'/templates/page.tpl',
			'/templates/post.tpl',
			'/PagesUrlBuilder.class.php',
			'/action.php',
			'/admin_pages.php',
			'/explorer.php',
			'/pages.php',
			'/pages_begin.php',
			'/pages_defines.php',
			'/pages_functions.php',
			'/post.php',
			'/print.php',
			'/xmlhttprequest.php'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'pages',
				'columns' => array(
					'id_cat'        => 'id_category INT(11) NOT NULL DEFAULT 0',
					'encoded_title' => 'rewrited_title VARCHAR(255) NOT NULL DEFAULT ""',
					'contents'      => 'content MEDIUMTEXT',
					'user_id'       => 'author_user_id INT(11) NOT NULL DEFAULT 0',
					'timestamp'     => 'creation_date INT(11) NOT NULL DEFAULT 0',
					'hits'          => 'views_number INT(11) NOT NULL DEFAULT 0',
				)
			)
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'pages',
				'columns' => array(
					'i_order'            => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'author_display'     => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
					'author_custom_name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
					'publication'        => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
					'start_date'         => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'end_date'           => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'updated_date'       => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'thumbnail_url'      => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
					'sources'            => array('type' => 'text', 'length' => 65000)
				)
			),
			array(
				'table_name' => PREFIX . 'pages_cats',
				'columns' => array(
					'name'                   => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
					'rewrited_name'          => array('type' => 'string', 'length' => 250, 'default' => "''"),
					'c_order'                => array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0),
					'special_authorizations' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
					'auth'                   => array('type' => 'text', 'length' => 65000),
					'id_parent'              => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'description'            => array('type' => 'text', 'length' => 65000),
					'thumbnail'              => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")
				)
			)
		);

		$this->database_keys_to_add = array(
			array(
				'table_name' => PREFIX . 'pages',
				'indexes' => array(
					'id_category',
				)
			)
		);

		$this->database_columns_to_delete = array(
			array(
				'table_name' => PREFIX . 'pages',
				'columns' => array(
					'auth',
					'is_cat',
					'count_hits',
					'display_print_link',
					'activ_com',
					'redirect',
				)
			),
			array(
				'table_name' => PREFIX . 'pages_cats',
				'columns' => array(
					'id_page'
				)
			)
		);
	}

}
?>
