<?php
/*##################################################
 *                        AgendaEvent.class.php
 *                            -------------------
 *   begin                : February 25, 2013
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

class AgendaEvent
{
	private $id;

	private $content;

	private $start_date;
	private $end_date;

	private $parent_id;

	private $participants = array();

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_content(AgendaEventContent $content)
	{
		$this->content = $content;
	}

	public function get_content()
	{
		return $this->content;
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

	public function unset_end_date()
	{
		$this->end_date = null;
	}

	public function get_end_date()
	{
		return $this->end_date;
	}

	public function set_parent_id($id)
	{
		$this->parent_id = $id;
	}

	public function get_parent_id()
	{
		return $this->parent_id;
	}

	public function set_participants(Array $participants)
	{
		$this->participants = $participants;
	}

	public function get_participants()
	{
		return $this->participants;
	}

	public function belongs_to_a_serie()
	{
		return $this->parent_id || $this->content->is_repeatable();
	}

	public function get_registered_members_number()
	{
		return count($this->participants);
	}

	public function is_authorized_to_add()
	{
		return AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->write() || AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->contribution();
	}

	public function is_authorized_to_edit()
	{
		return AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->moderation() || ((AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->write() || (AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->contribution())) && $this->content->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->moderation() || ((AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->write() || (AgendaAuthorizationsService::check_authorizations($this->content->get_category_id())->contribution() && !$this->content->is_approved())) && $this->content->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id_event' => $this->get_id(),
			'content_id' => $this->content->get_id(),
			'start_date' => $this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : '',
			'end_date' => $this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : '',
			'parent_id' => $this->get_parent_id()
		);
	}

	public function set_properties(array $properties)
	{
		$content = new AgendaEventContent();
		$content->set_properties($properties);

		$this->id = $properties['id_event'];
		$this->content = $content;
		$this->start_date = !empty($properties['start_date']) ? new Date($properties['start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date = !empty($properties['end_date']) ? new Date($properties['end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->parent_id = $properties['parent_id'];
	}

	public function init_default_properties($year, $month, $day)
	{
		$date = mktime(date('H'), date('i'), date('s'), $month, $day, $year);

		$this->start_date = new Date($this->round_to_five_minutes($date), Timezone::SERVER_TIMEZONE);

		$this->parent_id = 0;
		$this->participants = array();
	}

	public function get_array_tpl_vars()
	{
		$lang = LangLoader::get('common', 'agenda');

		$category = $this->content->get_category();
		$author = $this->content->get_author_user();
		$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);

		$contents = FormatingHelper::second_parse($this->content->get_contents());
		$short_contents = FormatingHelper::second_parse($this->content->get_short_contents());

		$missing_participants_number = $this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() < $this->content->get_max_registered_members() ? ($this->content->get_max_registered_members() - $this->get_registered_members_number()) : 0;

		$registration_days_left = $this->content->get_last_registration_date() && time() < $this->content->get_last_registration_date()->get_timestamp() ? (int)(($this->content->get_last_registration_date()->get_timestamp() - time()) /3600 /24) : 0;

		$contact_informations = $this->content->get_contact_informations();
		$nbr_contact = count($contact_informations);

		$path_informations = $this->content->get_path_informations();
		$nbr_path = count($path_informations);

		$event_picture = substr($this->content->get_picture()->rel(), -3);

		return array(
			'C_APPROVED' => $this->content->is_approved(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_LOCATION' => !empty($this->content->get_location()),
			'C_LOCATION_MORE' => !empty($this->content->get_location_more()),
			'C_BELONGS_TO_A_SERIE' => $this->belongs_to_a_serie(),
			'C_PARTICIPATION_ENABLED' => $this->content->is_registration_authorized(),
			'C_DISPLAY_PARTICIPANTS' => $this->content->is_authorized_to_display_registered_users(),
			'C_PARTICIPANTS' => !empty($this->participants),
			'C_PARTICIPATE' => $this->content->is_registration_authorized() && $this->content->is_authorized_to_register() && time() < $this->start_date->get_timestamp() && !in_array(AppContext::get_current_user()->get_id(), array_keys($this->participants)),
			'C_IS_PARTICIPANT' => in_array(AppContext::get_current_user()->get_id(), array_keys($this->participants)),
			'C_REGISTRATION_CLOSED' => $this->content->is_last_registration_date_enabled() && $this->content->get_last_registration_date() && time() > $this->content->get_last_registration_date()->get_timestamp(),
			'C_MAX_PARTICIPANTS_REACHED' => $this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() == $this->content->get_max_registered_members(),
			'C_MISSING_PARTICIPANTS' => !empty($missing_participants_number) && $missing_participants_number <= 5,
			'C_REGISTRATION_DAYS_LEFT' => !empty($registration_days_left) && $registration_days_left <= 5,
			'C_AUTHOR_GROUP_COLOR' => !empty($author_group_color),
			'C_AUTHOR_EXIST' => $author->get_id() !== User::VISITOR_LEVEL,
			'C_READ_MORE' => $contents != $short_contents && strlen($contents) > 250,
			'C_END_DATE' => $this->end_date !== null,
			'C_PICTURE' => $this->content->has_picture(),
			'C_PICTURE_IS_PDF' => $event_picture == 'pdf',
			'C_FORUM_LINK' => $this->content->has_forum_link(),
			'C_CANCELLED' => $this->content->is_cancelled(),
			'C_CONTACT_INFORMATIONS' => $nbr_contact > 0,
			'C_SEVERAL_CONTACTS' => $nbr_contact > 1,
			'C_PATH_INFORMATIONS' => $nbr_path > 0,
			'C_SEVERAL_PATHS' => $nbr_path > 1,

			//Event
			'ID' => $this->id,
			'CONTENT_ID' => $this->content->get_id(),
			'TITLE' => $this->content->get_title(),
			'CONTENTS' => $contents,
			'SHORT_CONTENTS' => $short_contents,
			'LOCATION' => $this->content->get_location(),
			'LOCATION_MORE' => $this->content->get_location_more(),
			'START_DATE' => $this->start_date->format(Date::FORMAT_DAY_MONTH_YEAR),
			'START_DATE_SHORT' => $this->start_date->format(Date::FORMAT_DAY_MONTH_YEAR),
			'START_DATE_DAY' => $this->start_date->get_day(),
			'START_DATE_MONTH' => $this->start_date->get_month(),
			'START_DATE_YEAR' => $this->start_date->get_year(),
			'START_DATE_HOUR' => $this->start_date->get_hours(),
			'START_DATE_MINUTE' => $this->start_date->get_minutes(),
			'START_DATE_ISO8601' => $this->start_date->format(Date::FORMAT_ISO8601),
			'END_DATE' => $this->end_date !== null ? $this->end_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) : '',
			'END_DATE_SHORT' => $this->end_date !== null ? $this->end_date->format(Date::FORMAT_DAY_MONTH_YEAR) : '',
			'END_DATE_DAY' => $this->end_date !== null ? $this->end_date->get_day() : '',
			'END_DATE_MONTH' => $this->end_date !== null ? $this->end_date->get_month() : '',
			'END_DATE_YEAR' => $this->end_date !== null ? $this->end_date->get_year() : '',
			'END_DATE_HOUR' => $this->end_date !== null ? $this->end_date->get_hours() : '',
			'END_DATE_MINUTE' => $this->end_date !== null ? $this->end_date->get_minutes() : '',
			'END_DATE_ISO8601' => $this->end_date !== null ? $this->end_date->format(Date::FORMAT_ISO8601) : '',
			'NUMBER_COMMENTS' => CommentsService::get_number_comments('agenda', $this->id),
			'L_COMMENTS' => CommentsService::get_number_and_lang_comments('agenda', $this->id),
			'REPEAT_TYPE' => $lang['agenda.labels.repeat.' . $this->content->get_repeat_type()],
			'REPEAT_NUMBER' => $this->content->get_repeat_number(),
			'AUTHOR' => $author->get_display_name(),
			'AUTHOR_LEVEL_CLASS' => UserService::get_level_class($author->get_level()),
			'AUTHOR_GROUP_COLOR' => $author_group_color,
			'NB_REGISTRED' => $this->get_registered_members_number(),
			'L_MISSING_PARTICIPANTS' => $missing_participants_number > 1 ? StringVars::replace_vars($lang['agenda.labels.remaining_places'], array('missing_number' => $missing_participants_number)) : $lang['agenda.labels.remaining_place'],
			'L_REGISTRATION_DAYS_LEFT' => $registration_days_left > 1 ? StringVars::replace_vars($lang['agenda.labels.remaining_days'], array('days_left' => $registration_days_left)) : $lang['agenda.labels.remaining_day'],
			'NBR_PATH' => $nbr_path,

			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_COLOR' => $category->get_id() != Category::ROOT_CATEGORY ? $category->get_color() : '',
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? AgendaUrlBuilder::configuration()->rel() : AgendaUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION' => SyndicationUrlBuilder::rss('agenda', $category->get_id())->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->rel(),
			'U_LINK' => AgendaUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $this->id, $this->content->get_rewrited_title())->rel(),
			'U_EDIT' => AgendaUrlBuilder::edit_event(!$this->parent_id ? $this->id : $this->parent_id)->rel(),
			'U_DELETE' => AgendaUrlBuilder::delete_event($this->id)->rel(),
			'U_SUSCRIBE' => AgendaUrlBuilder::suscribe_event($this->id)->rel(),
			'U_UNSUSCRIBE' => AgendaUrlBuilder::unsuscribe_event($this->id)->rel(),
			'U_PICTURE' => $this->content->get_picture()->rel(),
			'U_FORUM_LINK' => $this->content->get_forum_link()->rel(),
			'U_COMMENTS' => AgendaUrlBuilder::display_event_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->content->get_rewrited_title())->rel()
		);
	}

	private function round_to_five_minutes($timestamp)
	{
		if (($timestamp % 300) < 150)
			return $timestamp - ($timestamp % 300);
		else
			return $timestamp - ($timestamp % 300) + 300;
	}
}
?>
