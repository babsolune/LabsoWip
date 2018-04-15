<?php
/*##################################################
 *                               ClubsSetup.class.php
 *                            -------------------
 *   begin                : June 23, 2017
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class ClubsSetup extends DefaultModuleSetup
{
	public static $clubs_table;
	public static $clubs_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$clubs_table = PREFIX . 'clubs';
		self::$clubs_cats_table = PREFIX . 'clubs_cats';
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
		ConfigManager::delete('clubs', 'config');
		CacheManager::invalidate('module', 'clubs');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$clubs_table, self::$clubs_cats_table));
	}

	private function create_tables()
	{
		$this->create_clubs_table();
		$this->create_clubs_cats_table();
	}

	private function create_clubs_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'website_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'approbation_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_views' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'logo_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'logo_mini_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'colors' => array('type' => 'text', 'length' => 65000),
			'location' => array('type' => 'text', 'length' => 65000),
			'stadium_address' => array('type' => 'decimal', 'length' => 18, 'scale' => 15, 'notnull' => 1, 'default' => 0),
			'latitude' => array('type' => 'decimal', 'length' => 18, 'scale' => 15, 'notnull' => 1, 'default' => 0),
			'longitude' => array('type' => 'decimal', 'length' => 18, 'scale' => 15, 'notnull' => 1, 'default' => 0),
            'club_email' => array('type' => 'text', 'length' => 65000),
			'club_phone' => array('type' => 'text', 'length' => 65000),
			'district' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
			'facebook_link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'twitter_link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'gplus_link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'name'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$clubs_table, $fields, $options);
	}

	private function create_clubs_cats_table()
	{
		RichCategory::create_categories_table(self::$clubs_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'clubs');
		$this->insert_clubs_cats_data();
		$this->insert_clubs_data();
	}

	private function insert_clubs_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$clubs_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'image' => '/clubs/templates/images/default-category.png',
		));
	}

	private function insert_clubs_data()
	{
		PersistenceContext::get_querier()->insert(self::$clubs_table, array(
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['default.club.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.club.name']),
			'website_url' => 'http://www.stadefoyen.fr',
			'contents' => $this->messages['default.club.content'],
			'approbation_type' => Club::APPROVAL_NOW,
			'creation_date' => time(),
			'author_user_id' => 1,
			'number_views' => 0,
			'logo_url' => '/clubs/templates/images/logo.png',
			'logo_mini_url' => '/clubs/templates/images/logo_mini.png',
			'colors' => TextHelper::serialize(array()),
			'location' => TextHelper::serialize(array()),
            'latitude' => '44.839668465791670',
            'longitude' => '0.205439200000001'
		));
	}
}
?>
