<?php
/*##################################################
 *                               Member.class.php
 *                            -------------------
 *   begin                : June 29, 2017
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
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
 */

class Member
{
	private $id;
	private $id_category;
	private $lastname;
	private $firstname;
	private $rewrited_name;
	private $contents;
	private $picture_url;
	private $role;
	private $member_phone;
	private $member_email;

	private $approbation_type;

	private $creation_date;
	private $author_user;

	private $group_leader;

	const NOT_APPROVAL = 0;
	const APPROVAL_NOW = 1;

	const DEFAULT_PICTURE = '/staff/templates/images/no_avatar.png';

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_category()
	{
		return StaffService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function get_lastname()
	{
		return $this->lastname;
	}

	public function set_lastname($lastname)
	{
		$this->lastname = $lastname;
	}

	public function get_firstname()
	{
		return $this->firstname;
	}

	public function set_firstname($firstname)
	{
		$this->firstname = $firstname;
	}

	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}

	public function set_rewrited_name($rewrited_name)
	{
		$this->rewrited_name = $rewrited_name;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_role()
	{
		return $this->role;
	}

	public function set_role($role)
	{
		$this->role = $role;
	}

	public function get_member_phone()
	{
		return $this->member_phone;
	}

	public function set_member_phone($member_phone)
	{
		$this->member_phone = $member_phone;
	}

	public function get_member_email()
	{
		return $this->member_email;
	}

	public function set_member_email($member_email)
	{
		$this->member_email = $member_email;
	}

	public function get_approbation_type()
	{
		return $this->approbation_type;
	}

	public function set_approbation_type($approbation_type)
	{
		$this->approbation_type = $approbation_type;
	}

	public function is_visible()
	{
		$now = new Date();
		return StaffAuthorizationsService::check_authorizations($this->id_category)->read() && $this->get_approbation_type() == self::APPROVAL_NOW;
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

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_picture()
	{
		return $this->picture_url;
	}

	public function set_picture(Url $picture)
	{
		$this->picture_url = $picture;
	}

	public function has_picture()
	{
		$picture = $this->picture_url->rel();
		return !empty($picture);
	}

	public function is_group_leader()
	{
		return $this->group_leader;
	}

	public function set_group_leader($group_leader)
	{
		$this->group_leader = $group_leader;
	}

	public function is_authorized_to_add()
	{
		return StaffAuthorizationsService::check_authorizations($this->id_category)->write() || StaffAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return StaffAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((StaffAuthorizationsService::check_authorizations($this->id_category)->write() || (StaffAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return StaffAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((StaffAuthorizationsService::check_authorizations($this->id_category)->write() || (StaffAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'lastname' => $this->get_lastname(),
			'firstname' => $this->get_firstname(),
			'rewrited_name' => $this->get_rewrited_name(),
			'contents' => $this->get_contents(),
			'role' => $this->get_role(),
			'member_phone' => $this->get_member_phone(),
			'member_email' => $this->get_member_email(),
			'approbation_type' => $this->get_approbation_type(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'picture_url' => $this->get_picture()->relative(),
			'group_leader' => (int)$this->is_group_leader()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->lastname = $properties['lastname'];
		$this->firstname = $properties['firstname'];
		$this->rewrited_name = $properties['rewrited_name'];
		$this->contents = $properties['contents'];
		$this->role = $properties['role'];
		$this->member_phone = $properties['member_phone'];
		$this->member_email = $properties['member_email'];
		$this->approbation_type = $properties['approbation_type'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->picture_url = new Url($properties['picture_url']);
		$this->group_leader = (bool)$properties['group_leader'];

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
		$this->approbation_type = self::APPROVAL_NOW;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->picture_url = new Url(self::DEFAULT_PICTURE);
	}

	public function get_array_tpl_vars()
	{
		$category = $this->get_category();
		$contents = FormatingHelper::second_parse($this->contents);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$new_content= new StaffNewContent();

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			array(
			'C_VISIBLE' => $this->is_visible(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_PICTURE' => $this->has_picture(),
			'C_IS_GROUP_LEADER' => $this->is_group_leader(),
			'C_NEW_CONTENT' => $new_content->check_if_is_new_content($this->get_creation_date()->get_timestamp()) && $this->is_visible(),
            'C_ROLE' => !empty($this->role),
            'C_MEMBER_PHONE' => !empty($this->member_phone),
            'C_MEMBER_EMAIL' => !empty($this->member_email),
			//Member
			'ID' => $this->id,
			'LASTNAME' => $this->lastname,
			'FIRSTNAME' => $this->firstname,
			'ROLE' => $this->role,
			'MEMBER_PHONE' => $this->member_phone,
			'MEMBER_EMAIL' => $this->member_email,
			'CONTENTS' => $contents,
			'STATUS' => $this->get_status(),
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO' => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,

			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image()->rel(),
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? StaffUrlBuilder::configuration()->rel() : StaffUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION' => SyndicationUrlBuilder::rss('staff', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_MEMBER' => StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_CATEGORY' => StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => StaffUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => StaffUrlBuilder::delete($this->id)->rel(),
			'U_PICTURE' => $this->get_picture()->rel()
			)
		);
	}
}
?>
