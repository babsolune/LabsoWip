<?php
/*##################################################
 *                        AgendaEventContent.class.php
 *                            -------------------
 *   begin                : October 29, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AgendaEventContent
{
	private $id;
	private $category_id;
	private $title;
	private $rewrited_title;
	private $contents;

	private $location;
	private $location_more;

	private $approved;

	private $creation_date;
	private $author_user;

	private $registration_authorized;
	private $max_registered_members;
	private $last_registration_date_enabled;
	private $last_registration_date;
	private $register_authorizations;

	private $repeat_number;
	private $repeat_type;

	private $picture_url;
	private $forum_link;
	private $contact_informations;
	private $path_informations;
	private $cancelled;

	const NEVER = 'never';
	const DAILY = 'daily';
	const WEEKLY = 'weekly';
	const MONTHLY = 'monthly';
	const YEARLY = 'yearly';

	const DISPLAY_REGISTERED_USERS_AUTHORIZATION = 1;
	const REGISTER_AUTHORIZATION = 2;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_category_id($category_id)
	{
		$this->category_id = $category_id;
	}

	public function get_category_id()
	{
		return $this->category_id;
	}

	public function get_category()
	{
		return AgendaService::get_categories_manager()->get_categories_cache()->get_category($this->category_id);
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

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function get_short_contents()
	{
		return substr($this->contents, 0, 250);
	}

	public function set_location($location)
	{
		$this->location = $location;
	}

	public function get_location()
	{
		return $this->location;
	}

	public function set_location_more($location_more)
	{
		$this->location_more = $location_more;
	}

	public function get_location_more()
	{
		return $this->location_more;
	}

	public function approve()
	{
		$this->approved = true;
	}

	public function unapprove()
	{
		$this->approved = false;
	}

	public function is_approved()
	{
		return $this->approved;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_author_user(User $author)
	{
		$this->author_user = $author;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function authorize_registration()
	{
		$this->registration_authorized = true;
	}

	public function unauthorize_registration()
	{
		$this->registration_authorized = false;
	}

	public function is_registration_authorized()
	{
		return $this->registration_authorized;
	}

	public function set_max_registered_members($max_registered_members)
	{
		$this->max_registered_members = $max_registered_members;
	}

	public function get_max_registered_members()
	{
		return $this->max_registered_members;
	}

	public function enable_last_registration_date()
	{
		$this->last_registration_date_enabled = true;
	}

	public function disable_last_registration_date()
	{
		$this->last_registration_date_enabled = false;
	}

	public function is_last_registration_date_enabled()
	{
		return $this->last_registration_date_enabled;
	}

	public function set_last_registration_date($last_registration_date)
	{
		$this->last_registration_date = $last_registration_date;
	}

	public function get_last_registration_date()
	{
		return $this->last_registration_date;
	}

	public function set_register_authorizations(array $authorizations)
	{
		$this->register_authorizations = $authorizations;
	}

	public function get_register_authorizations()
	{
		return $this->register_authorizations;
	}

	public function is_authorized_to_display_registered_users()
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::DISPLAY_REGISTERED_USERS_AUTHORIZATION);
	}

	public function is_authorized_to_register()
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::REGISTER_AUTHORIZATION);
	}

	public function set_repeat_number($number)
	{
		$this->repeat_number = $number;
	}

	public function get_repeat_number()
	{
		return $this->repeat_number;
	}

	public function set_repeat_type($type)
	{
		$this->repeat_type = $type;
	}

	public function get_repeat_type()
	{
		return $this->repeat_type;
	}

	public function is_repeatable()
	{
		return $this->repeat_type != self::NEVER;
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

	public function get_forum_link()
	{
		return $this->forum_link;
	}

	public function set_forum_link(Url $forum_link)
	{
		$this->forum_link = $forum_link;
	}

	public function has_forum_link()
	{
		$forum_link = $this->forum_link->rel();
		return !empty($forum_link);
	}

	public function get_contact_informations()
	{
		return $this->contact_informations;
	}

	public function set_contact_informations($contact_informations)
	{
		$this->contact_informations = $contact_informations;
	}

	public function get_path_informations()
	{
		return $this->path_informations;
	}

	public function set_path_informations($path_informations)
	{
		$this->path_informations = $path_informations;
	}

	public function cancel()
	{
		$this->cancelled = true;
	}

	public function uncancel()
	{
		$this->cancelled = false;
	}

	public function is_cancelled()
	{
		return $this->cancelled;
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_category_id(),
			'title' => TextHelper::htmlspecialchars($this->get_title()),
			'rewrited_title' => TextHelper::htmlspecialchars($this->get_rewrited_title()),
			'contents' => $this->get_contents(),
			'location' => TextHelper::serialize($this->get_location()),
			'location_more' => $this->get_location_more(),
			'approved' => (int)$this->is_approved(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_id' => $this->get_author_user()->get_id(),
			'registration_authorized' => (int)$this->is_registration_authorized(),
			'max_registered_members' => $this->get_max_registered_members(),
			'last_registration_date' => $this->get_last_registration_date() !== null ? $this->get_last_registration_date()->get_timestamp() : '',
			'register_authorizations' => serialize($this->get_register_authorizations()),
			'repeat_number' => $this->get_repeat_number(),
			'repeat_type' => $this->get_repeat_type(),
			'picture_url' => $this->get_picture()->relative(),
			'forum_link' => $this->get_forum_link()->relative(),
			'contact_informations' => TextHelper::serialize($this->get_contact_informations()),
			'path_informations' => TextHelper::serialize($this->get_path_informations()),
			'cancelled' => (int)$this->is_cancelled()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->category_id = $properties['id_category'];
		$this->title = $properties['title'];
		$this->rewrited_title = $properties['rewrited_title'];
		$this->contents = $properties['contents'];
		$this->location = !empty($properties['location']) ? TextHelper::unserialize($properties['location']) : array();
		$this->location_more = $properties['location_more'];

		if ($properties['approved'])
			$this->approve();
		else
			$this->unapprove();

		if ($properties['registration_authorized'])
			$this->authorize_registration();
		else
			$this->unauthorize_registration();

		$this->max_registered_members = $properties['max_registered_members'];
		$this->last_registration_date_enabled = !empty($properties['last_registration_date']);
		$this->last_registration_date = !empty($properties['last_registration_date']) ? new Date($properties['last_registration_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->register_authorizations = unserialize($properties['register_authorizations']);

		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);

		$this->repeat_number = $properties['repeat_number'];
		$this->repeat_type = $properties['repeat_type'];

		$this->picture_url = new Url($properties['picture_url']);
		$this->forum_link = new Url($properties['forum_link']);
		$this->contact_informations = !empty($properties['contact_informations']) ? TextHelper::unserialize($properties['contact_informations']) : array();
		$this->path_informations = !empty($properties['path_informations']) ? TextHelper::unserialize($properties['path_informations']) : array();

		if ($properties['cancelled'])
			$this->cancel();
		else
			$this->uncancel();

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);
	}

	public function init_default_properties($category_id = Category::ROOT_CATEGORY)
	{
		$this->category_id = $category_id;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();

		$this->registration_authorized = true;
		$this->max_registered_members = 0;
		$this->last_registration_date_enabled = false;
		$this->register_authorizations = array('r0' => 3, 'r1' => 3);

		if (AgendaAuthorizationsService::check_authorizations()->write())
			$this->approve();
		else
			$this->unapprove();

		$this->repeat_number = 1;
		$this->repeat_type = self::NEVER;

		$this->location = array();
		$this->contact_informations = array();
		$this->path_informations = array();
		$this->picture_url = new Url('');
		$this->forum_link = new Url('');
		$this->cancelled = false;
	}
}
?>
