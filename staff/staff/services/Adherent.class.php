<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
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
	private $thumbnail_url;
	private $role;
	private $item_phone;
	private $item_email;
	private $group_leader;

	private $creation_date;
	private $author_user;

	private $is_published;

    const NOT_PUBLISHED = 0;
    const PUBLISHED = 1;

	const DEFAULT_THUMBNAIL = '/staff/templates/images/no_avatar.png';

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

	public function get_item_phone()
	{
		return $this->item_phone;
	}

	public function set_item_phone($item_phone)
	{
		$this->item_phone = $item_phone;
	}

	public function get_item_email()
	{
		return $this->item_email;
	}

	public function set_item_email($item_email)
	{
		$this->item_email = $item_email;
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

	public function get_thumbnail()
	{
		return $this->thumbnail_url;
	}

	public function set_thumbnail(Url $thumbnail)
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function has_thumbnail()
	{
		$thumbnail = $this->thumbnail_url->rel();
		return !empty($thumbnail);
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
			'order_id'              => $this->get_order_id(),
			'lastname' => $this->get_lastname(),
			'firstname' => $this->get_firstname(),
			'rewrited_name' => $this->get_rewrited_name(),
			'contents' => $this->get_contents(),
			'role' => $this->get_role(),
			'item_phone' => $this->get_item_phone(),
			'item_email' => $this->get_item_email(),
			'publication'       => (int)$this->is_published(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'thumbnail_url' => $this->get_thumbnail()->relative(),
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
		$this->item_phone = $properties['item_phone'];
		$this->item_email = $properties['item_email'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->thumbnail_url = new Url($properties['thumbnail_url']);
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
		$this->thumbnail_url = new Url(self::DEFAULT_THUMBNAIL);

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

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			array(
			'C_VISIBLE' => $this->is_visible(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_HAS_THUMBNAIL' => $this->has_thumbnail(),
			'C_IS_GROUP_LEADER' => $this->is_group_leader(),
			'C_ROLE' => !empty($this->role),
			'C_NEW_CONTENT' => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('staff', $this->get_creation_date()->get_timestamp()) && $this->is_visible(),
            'C_ITEM_PHONE' => !empty($this->item_phone),
            'C_ITEM_EMAIL' => !empty($this->item_email),
			//Adherent
			'ID' => $this->id,
			'LASTNAME' => $this->lastname,
			'FIRSTNAME' => $this->firstname,
			'ROLE' => $this->role,
			'ITEM_PHONE' => $this->item_phone,
			'ITEM_EMAIL' => $this->item_email,
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
			'U_ITEM' => StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_CATEGORY' => StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => StaffUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => StaffUrlBuilder::delete($this->id)->rel(),
			'U_THUMBNAIL' => $this->get_thumbnail()->rel()
			)
		);
	}
}
?>
