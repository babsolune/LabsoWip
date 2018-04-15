<?php
/*##################################################
 *		                         Radio.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class Radio
{
	private $id;
	private $id_cat;
	private $name;
	private $rewrited_name;
	private $contents;

	private $approbation_type;
	private $release_day;
	private $start_date;
	private $end_date;
	private $extra_list_enabled;

	private $author_user;
	private $author_custom_name;

	private $picture_url;

	const NOT_APPROVAL = 0;
	const APPROVAL_NOW = 1;

	const MONDAY = 1;
	const TUESDAY = 2;
	const WEDNESDAY = 3;
	const THURSDAY = 4;
	const FRIDAY = 5;
	const SATURDAY = 6;
	const SUNDAY = 7;

	const DEFAULT_PICTURE = '/radio/templates/images/default.jpg';

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id_cat($id_cat)
	{
		$this->id_cat = $id_cat;
	}

	public function get_id_cat()
	{
		return $this->id_cat;
	}

	public function get_category()
	{
		return RadioService::get_categories_manager()->get_categories_cache()->get_category($this->id_cat);
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_rewrited_name($rewrited_name)
	{
		$this->rewrited_name = $rewrited_name;
	}

	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}

	public function rewrited_name_is_personalized()
	{
		return $this->rewrited_name != Url::encode_rewrite($this->name);
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_release_day($release_day)
	{
		$this->release_day = $release_day;
	}

	public function get_release_day()
	{
		return $this->release_day;
	}

	public function get_calendar()
	{
		switch ($this->release_day) {
			case self::MONDAY:
				return LangLoader::get_message('form.monday', 'common', 'radio');
			break;
			case self::TUESDAY:
				return LangLoader::get_message('form.tuesday', 'common', 'radio');
			break;
			case self::WEDNESDAY:
				return LangLoader::get_message('form.wednesday', 'common', 'radio');
			break;
			case self::THURSDAY:
				return LangLoader::get_message('form.thursday', 'common', 'radio');
			break;
			case self::FRIDAY:
				return LangLoader::get_message('form.friday', 'common', 'radio');
			break;
			case self::SATURDAY:
				return LangLoader::get_message('form.saturday', 'common', 'radio');
			break;
			case self::SUNDAY:
				return LangLoader::get_message('form.sunday', 'common', 'radio');
			break;
		}
	}

	public function set_approbation_type($approbation_type)
	{
		$this->approbation_type = $approbation_type;
	}

	public function get_approbation_type()
	{
		return $this->approbation_type;
	}

	public function is_visible()
	{
		$now = new Date();
		return RadioAuthorizationsService::check_authorizations($this->id_cat)->read() && $this->get_approbation_type() == self::APPROVAL_NOW;
	}

	public function get_status()
	{
		switch ($this->approbation_type) {
			case self::APPROVAL_NOW:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::NOT_APPROVAL:
				return LangLoader::get_message('status.approved.not', 'common');
			break;
		}
	}

	public function set_start_date(Date $start_date)
	{
		$this->start_date = $start_date;
	}

	public function get_start_date()
	{
		return $this->start_date;
	}

	public function set_end_date(Date $end_date)
	{
		$this->end_date = $end_date;
	}

	public function get_end_date()
	{
		return $this->end_date;
	}

	public function set_extra_list_enabled($extra_list_enabled)
	{
		$this->extra_list_enabled = $extra_list_enabled;
	}

	public function extra_list_enabled()
	{
		return $this->extra_list_enabled;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function get_author_custom_name()
	{
		return $this->author_custom_name;
	}

	public function set_author_custom_name($author_custom_name)
	{
		$this->author_custom_name = $author_custom_name;
	}

	public function set_picture(Url $picture)
	{
		$this->picture_url = $picture;
	}

	public function get_picture()
	{
		return $this->picture_url;
	}

	public function has_picture()
	{
		$picture = $this->picture_url->rel();
		return !empty($picture);
	}

	public function is_authorized_to_add()
	{
		return RadioAuthorizationsService::check_authorizations($this->id_cat)->write() || RadioAuthorizationsService::check_authorizations($this->id_cat)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return RadioAuthorizationsService::check_authorizations($this->id_cat)->moderation() || ((RadioAuthorizationsService::check_authorizations($this->get_id_cat())->write() || (RadioAuthorizationsService::check_authorizations($this->get_id_cat())->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return RadioAuthorizationsService::check_authorizations($this->id_cat)->moderation() || ((RadioAuthorizationsService::check_authorizations($this->get_id_cat())->write() || (RadioAuthorizationsService::check_authorizations($this->get_id_cat())->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_cat(),
			'name' => $this->get_name(),
			'rewrited_name' => $this->get_rewrited_name(),
			'contents' => $this->get_contents(),
			'approbation_type' => $this->get_approbation_type(),
			'release_day' => $this->get_release_day(),
			'start_date' => (int)($this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : ''),
			'end_date' => (int)($this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : ''),
			'extra_list_enabled' => (int)$this->extra_list_enabled(),
			'author_custom_name' => $this->get_author_custom_name(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'picture_url' => $this->get_picture()->relative()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_cat = $properties['id_category'];
		$this->name = $properties['name'];
		$this->rewrited_name = $properties['rewrited_name'];
		$this->contents = $properties['contents'];
		$this->release_day = $properties['release_day'];
		$this->approbation_type = $properties['approbation_type'];
		$this->start_date = !empty($properties['start_date']) ? new Date($properties['start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date = !empty($properties['end_date']) ? new Date($properties['end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->extra_list_enabled = (bool)$properties['extra_list_enabled'];
		$this->picture_url = new Url($properties['picture_url']);

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$this->author_custom_name = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : $this->author_user->get_display_name();
	}

	public function init_default_properties($id_cat = Category::ROOT_CATEGORY)
	{
		$this->id_cat = $id_cat;
		$this->approbation_type = self::APPROVAL_NOW;
		$this->author_user = AppContext::get_current_user();
		$this->start_date = new Date();
		$this->end_date = new Date();
		$this->release_day = self::MONDAY;
		$this->picture_url = new Url(self::DEFAULT_PICTURE);
		$this->author_custom_name = $this->author_user->get_display_name();
	}

	public function get_array_tpl_vars()
	{
		$radio_config = RadioConfig::load();
		$category = $this->get_category();
		$contents = FormatingHelper::second_parse($this->contents);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		return array_merge(
			// Date::get_array_tpl_vars($this->start_date, 'start_date'),
			// Date::get_array_tpl_vars($this->end_date, 'end_date'),
			array(
			'C_VISIBLE' => $this->is_visible(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_PICTURE' => $this->has_picture(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_EXTRA_LIST' => $this->extra_list_enabled(),

			//Radio
			'ID' => $this->id,
			'NAME' => $this->name,
			'CONTENTS' => $contents,
			'RELEASE_DAY' => $this->get_release_day(),
			'STATUS' => $this->get_status(),
			'CALENDAR' => $this->get_calendar(),
			'AUTHOR_CUSTOM_NAME' => $this->author_custom_name,
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO' => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,

			// date
			'RELEASE_DAY' => $this->get_release_day(),
			'START_HOURS' => $this->start_date->get_hours(),
			'START_MINUTES' => $this->start_date->get_minutes(),
			'END_HOURS' => $this->end_date->get_hours(),
			'END_MINUTES' => $this->end_date->get_minutes(),
			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image()->rel(),
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? RadioUrlBuilder::configuration()->rel() : RadioUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION' => SyndicationUrlBuilder::rss('radio', $this->id_cat)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_LINK' => RadioUrlBuilder::display_radio($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_CATEGORY' => RadioUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => RadioUrlBuilder::edit_radio($this->id)->rel(),
			'U_DELETE' => RadioUrlBuilder::delete_radio($this->id)->rel(),
			'U_PICTURE' => $this->get_picture()->rel()
		));
	}
}
?>
