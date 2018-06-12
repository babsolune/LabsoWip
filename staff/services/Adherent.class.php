<?php
/*##################################################
 *                               Adherent.class.php
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

class Adherent
{
	private $id;
	private $id_category;
	private $order_id;
	private $lastname;
	private $firstname;
	private $rewrited_name;
	private $contents;
	private $picture_url;
	private $role;
	private $adherent_phone;
	private $adherent_email;
	private $group_leader;

	private $creation_date;
	private $author_user;

	private $is_published;

    const NOT_PUBLISHED = 0;
    const PUBLISHED = 1;

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

	public function get_order_id()
	{
		return $this->order_id;
	}

	public function set_order_id($order_id)
	{
		$this->order_id = $order_id;
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

	public function get_adherent_phone()
	{
		return $this->adherent_phone;
	}

	public function set_adherent_phone($adherent_phone)
	{
		$this->adherent_phone = $adherent_phone;
	}

	public function get_adherent_email()
	{
		return $this->adherent_email;
	}

	public function set_adherent_email($adherent_email)
	{
		$this->adherent_email = $adherent_email;
	}

	public function published()
	{
		$this->publication = true;
	}

	public function not_published()
	{
		$this->publication = false;
	}

	public function is_published()
	{
		return $this->publication;
	}

	public function is_visible()
	{
		$now = new Date();
		return StaffAuthorizationsService::check_authorizations($this->id_category)->read() && $this->is_published() == self::PUBLISHED;
	}

	public function get_status()
	{
		switch ($this->is_published) {
			case self::PUBLISHED:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::NOT_PUBLISHED:
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
		return StaffAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((StaffAuthorizationsService::check_authorizations($this->id_category)->write() || (StaffAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::ADHERENT_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return StaffAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((StaffAuthorizationsService::check_authorizations($this->id_category)->write() || (StaffAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::ADHERENT_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'order_id'              => $this->get_order_id(),
			'lastname' => $this->get_lastname(),
			'firstname' => $this->get_firstname(),
			'rewrited_name' => $this->get_rewrited_name(),
			'contents' => $this->get_contents(),
			'role' => $this->get_role(),
			'adherent_phone' => $this->get_adherent_phone(),
			'adherent_email' => $this->get_adherent_email(),
			'publication'       => (int)$this->is_published(),
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
		$this->order_id = $properties['order_id'];
		$this->lastname = $properties['lastname'];
		$this->firstname = $properties['firstname'];
		$this->rewrited_name = $properties['rewrited_name'];
		$this->contents = $properties['contents'];
		$this->role = $properties['role'];
		$this->adherent_phone = $properties['adherent_phone'];
		$this->adherent_email = $properties['adherent_email'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->picture_url = new Url($properties['picture_url']);
		$this->group_leader = (bool)$properties['group_leader'];

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		if ($properties['publication'])
			$this->published();
		else
			$this->not_published();
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->picture_url = new Url(self::DEFAULT_PICTURE);

		if (StaffAuthorizationsService::check_authorizations()->write())
			$this->published();
		else
			$this->not_published();
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
            'C_ADHERENT_PHONE' => !empty($this->adherent_phone),
            'C_ADHERENT_EMAIL' => !empty($this->adherent_email),
			//Adherent
			'ID' => $this->id,
			'LASTNAME' => $this->lastname,
			'FIRSTNAME' => $this->firstname,
			'ROLE' => str_replace('-',' ', $this->role),
			'ADHERENT_PHONE' => $this->adherent_phone,
			'ADHERENT_EMAIL' => $this->adherent_email,
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
			'U_ADHERENT' => StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_CATEGORY' => StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => StaffUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => StaffUrlBuilder::delete($this->id)->rel(),
			'U_PICTURE' => $this->get_picture()->rel()
			)
		);
	}
}
?>
