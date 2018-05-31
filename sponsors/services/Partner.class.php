<?php
/*##################################################
 *                        Partner.class.php
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

class Partner
{
	private $id;
	private $id_category;
	private $title;
	private $rewrited_title;
	private $contents;
	private $website_url;
	private $thumbnail_url;
	private $views_number;
	private $visits_number;
	private $partner_level;

	private $author_user;
	private $published;
	private $publication_start_date;
	private $publication_end_date;
	private $creation_date;
	private $enabled_end_date;
	private $updated_date;

	const NOT_PUBLISHED = 0;
	const PUBLISHED_NOW = 1;
	const PUBLICATION_DATE = 2;

	const DEFAULT_PICTURE = '/sponsors/templates/images/default.png';

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function get_category()
	{
		return SponsorsService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}

	public function rewrited_title_is_personalized()
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}

	public function set_website(Url $website)
	{
		$this->website_url = $website;
	}

	public function get_website()
	{
		if (!$this->website_url instanceof Url)
			return new Url('');

		return $this->website_url;
	}

	public function has_website()
	{
		$website = $this->website_url->rel();
		return !empty($website);
	}

	public function set_partner_level($partner_level)
	{
		$this->partner_level = $partner_level;
	}

	public function get_partner_level()
	{
		return $this->partner_level;
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_thumbnail(Url $thumbnail)
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function get_thumbnail()
	{
		return $this->thumbnail_url;
	}

	public function has_thumbnail()
	{
		$thumbnail = $this->thumbnail_url->rel();
		return !empty($thumbnail);
	}

	public function set_views_number($views_number)
	{
		$this->views_number = $views_number;
	}

	public function get_views_number()
	{
		return $this->views_number;
	}

	public function set_visits_number($visits_number)
	{
		$this->visits_number = $visits_number;
	}

	public function get_visits_number()
	{
		return $this->visits_number;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
	    return $this->author_user;
	}

	public function set_publication_state($published)
	{
		$this->published = $published;
	}

	public function get_publication_state()
	{
		return $this->published;
	}

	public function is_published()
	{
		$now = new Date();
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_publication_state() == self::PUBLISHED_NOW || ($this->get_publication_state() == self::PUBLICATION_DATE && $this->get_publication_start_date()->is_anterior_to($now) && ($this->enabled_end_date ? $this->get_publication_end_date()->is_posterior_to($now) : true)));
	}

	public function get_status()
	{
		switch ($this->published) {
			case self::PUBLISHED_NOW:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::PUBLICATION_DATE:
				return LangLoader::get_message('status.approved.date', 'common');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('status.approved.not', 'common');
			break;
		}
	}

	public function set_publication_start_date(Date $publication_start_date)
	{
		$this->publication_start_date = $publication_start_date;
	}

	public function get_publication_start_date()
	{
		return $this->publication_start_date;
	}

	public function set_publication_end_date(Date $publication_end_date)
	{
		$this->publication_end_date = $publication_end_date;
		$this->enabled_end_date = true;
	}

	public function get_publication_end_date()
	{
		return $this->publication_end_date;
	}

	public function enabled_end_date()
	{
		return $this->enabled_end_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function get_updated_date()
	{
		return $this->updated_date;
	}

	public function set_updated_date(Date $updated_date)
	{
	    $this->updated_date = $updated_date;
	}

	public function is_authorized_to_add()
	{
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->write() || SponsorsAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((SponsorsAuthorizationsService::check_authorizations($this->get_id_category())->write() || (SponsorsAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))));
	}

	public function is_authorized_to_delete()
	{
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((SponsorsAuthorizationsService::check_authorizations($this->get_id_category())->write() || (SponsorsAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id'                     => $this->get_id(),
			'id_category'            => $this->get_id_category(),
			'title'                  => $this->get_title(),
			'rewrited_title'         => $this->get_rewrited_title(),
			'website_url'            => $this->get_website()->absolute(),
			'partner_level'          => $this->get_partner_level(),
			'contents'               => $this->get_contents(),
			'thumbnail_url'          => $this->get_thumbnail()->relative(),
			'views_number'           => $this->get_views_number(),
			'visits_number' 		 => $this->get_visits_number(),
			'author_user_id'         => $this->get_author_user()->get_id(),
			'published'              => $this->get_publication_state(),
			'publication_start_date' => $this->get_publication_start_date() !== null ? $this->get_publication_start_date()->get_timestamp() : 0,
			'publication_end_date'   => $this->get_publication_end_date() !== null ? $this->get_publication_end_date()->get_timestamp() : 0,
			'creation_date'          => $this->get_creation_date()->get_timestamp(),
			'updated_date'           => $this->get_updated_date() !== null ? $this->get_updated_date()->get_timestamp() : 0
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_category($properties['id_category']);
		$this->set_title($properties['title']);
		$this->set_rewrited_title($properties['rewrited_title']);
		$this->set_website(new Url($properties['website_url']));
		$this->set_partner_level($properties['partner_level']);
		$this->set_contents($properties['contents']);
		$this->set_thumbnail(new Url($properties['thumbnail_url']));
		$this->set_views_number($properties['views_number']);
		$this->set_visits_number($properties['visits_number']);
		$this->set_publication_state($properties['published']);
		$this->publication_start_date = !empty($properties['publication_start_date']) ? new Date($properties['publication_start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->publication_end_date = !empty($properties['publication_end_date']) ? new Date($properties['publication_end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->enabled_end_date = !empty($properties['publication_end_date']);
		$this->set_creation_date(new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE));
		$this->updated_date = !empty($properties['updated_date']) ? new Date($properties['updated_date'], Timezone::SERVER_TIMEZONE) : null;

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->author_user = AppContext::get_current_user();
		$this->website_url = new Url('');
		$this->thumbnail_url = new Url(self::DEFAULT_PICTURE);
		$this->views_number = 0;
		$this->visits_number = 0;
		$this->published = self::PUBLISHED_NOW;
		$this->publication_start_date = new Date();
		$this->publication_end_date = new Date();
		$this->creation_date = new Date();
	}

	public function clean_publication_start_and_end_date()
	{
		$this->publication_start_date = null;
		$this->publication_end_date = null;
		$this->enabled_end_date = false;
	}

	public function clean_publication_end_date()
	{
		$this->publication_end_date = null;
		$this->enabled_end_date = false;
	}

	public function get_array_tpl_vars()
	{
		$this->config = SponsorsConfig::load();
		$category           = $this->get_category();
		$contents	 	    = FormatingHelper::second_parse($this->contents);
		$user               = $this->get_author_user();
		$user_group_color   = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$new_content        = new SponsorsNewContent();

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			Date::get_array_tpl_vars($this->updated_date, 'updated_date'),
			Date::get_array_tpl_vars($this->publication_start_date, 'publication_start_date'),
			Date::get_array_tpl_vars($this->publication_end_date, 'publication_end_date'),
			array(
			//Conditions
			'C_EDIT'                           => $this->is_authorized_to_edit(),
			'C_DELETE'                         => $this->is_authorized_to_delete(),
			'C_HAS_THUMBNAIL'                  => $this->has_thumbnail() && file_exists(PATH_TO_ROOT . $this->get_thumbnail()->relative()),
			'C_HAS_WEBSITE'                    => $this->has_website(),
			'C_USER_GROUP_COLOR'               => !empty($user_group_color),
			'C_PUBLISHED'                      => $this->is_published(),
			'C_PUBLICATION_START_AND_END_DATE' => $this->publication_start_date != null && $this->publication_end_date != null,
			'C_PUBLICATION_START_DATE'         => $this->publication_start_date != null,
			'C_PUBLICATION_END_DATE'           => $this->publication_end_date != null,
			'C_UPDATED_DATE'                   => $this->updated_date != null,
			'C_DIFFERED'                       => $this->published == self::PUBLICATION_DATE,
			'C_NEW_CONTENT'                    => $new_content->check_if_is_new_content($this->publication_start_date != null ? $this->publication_start_date->get_timestamp() : $this->get_creation_date()->get_timestamp()) && $this->is_published(),

			//Sponsors
			'ID'                 	=> $this->get_id(),
			'TITLE'              	=> $this->get_title(),
			'STATUS'             	=> $this->get_status(),
			'VIEWS_NUMBER'       	=> $this->get_views_number(),
			'VISITS_NUMBER'       	=> $this->get_visits_number(),
			'C_AUTHOR_EXIST'     	=> $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO'             	=> $user->get_display_name(),
			'LEVEL'   				=> $this->get_partner_level(),
			'USER_LEVEL_CLASS'   	=> UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR'   	=> $user_group_color,

			//Category
			'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
			'ID_CATEGORY'          => $category->get_id(),
			'CATEGORY_NAME'        => $category->get_name(),
			'CATEGORY_REWRITED_NAME'        => $category->get_rewrited_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE'       => $category->get_image()->rel(),
			'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? SponsorsUrlBuilder::configuration()->rel() : SponsorsUrlBuilder::edit_category($category->get_id())->rel(),

			//Links
			'U_AUTHOR'      => UserUrlBuilder  ::profile($this->get_author_user()->get_id())->rel(),
			'U_CATEGORY'    => SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_ITEM'        => SponsorsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->rel(),
			'U_EDIT_ITEM'   => SponsorsUrlBuilder::edit_item($this->id, AppContext::get_request()->get_getint('page', 1))->rel(),
			'U_DELETE_ITEM' => SponsorsUrlBuilder::delete_item($this->id)->rel(),
			'U_SYNDICATION' => SponsorsUrlBuilder::category_syndication($category->get_id())->rel(),
			'U_THUMBNAIL'   => $this->get_thumbnail()->rel(),
			'U_DEADLINK'     => SponsorsUrlBuilder::dead_link($this->id)->rel(),
			'U_WEBSITE'     => SponsorsUrlBuilder::visit($this->id)->rel(),
			)
		);
	}
}
?>
