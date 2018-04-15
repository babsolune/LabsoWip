<?php
/*##################################################
 *                               Club.class.php
 *                            -------------------
 *   begin                : June 23, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   club_email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *club_phone
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

class Club
{
	private $id;
	private $id_category;
	private $name;
	private $rewrited_name;
	private $website_url;
    private $district;
    private $location;
    private $stadium_address;
    private $stadium_latitude;
    private $stadium_longitude;
    private $club_phone;
    private $club_email;
	private $contents;

	private $approbation_type;

	private $creation_date;
	private $author_user;
	private $number_views;
	private $logo_url;
	private $logo_mini_url;
    private $colors;

    private $facebook_link;
    private $twitter_link;
    private $gplus_link;

	private $notation;

	const NOT_APPROVAL = 0;
	const APPROVAL_NOW = 1;

	const DEFAULT_LOGO = '';

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
		return ClubsService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}

	public function set_rewrited_name($rewrited_name)
	{
		$this->rewrited_name = $rewrited_name;
	}

	public function get_website_url()
	{
		if (!$this->website_url instanceof Url)
			return new Url('');

		return $this->website_url;
	}

	public function set_website_url(Url $website_url)
	{
		$this->website_url = $website_url;
	}

	public function get_district()
	{
		return $this->district;
	}

	public function set_district($district)
	{
		$this->district = $district;
	}

	public function get_location()
	{
		return $this->location;
	}

	public function set_location($location)
	{
		$this->location = $location;
	}

	public function get_stadium_address()
	{
		return $this->stadium_address;
	}

	public function set_stadium_address($stadium_address)
	{
		$this->stadium_address = $stadium_address;
	}

	public function get_stadium_latitude()
	{
		return $this->stadium_latitude;
	}

	public function set_stadium_latitude($stadium_latitude)
	{
		$this->stadium_latitude = $stadium_latitude;
	}

	public function get_stadium_longitude()
	{
		return $this->stadium_longitude;
	}

	public function set_stadium_longitude($stadium_longitude)
	{
		$this->stadium_longitude = $stadium_longitude;
	}

	public function get_club_phone()
	{
		return $this->club_phone;
	}

	public function set_club_phone($club_phone)
	{
		$this->club_phone = $club_phone;
	}

	public function get_club_email()
	{
		return $this->club_email;
	}

	public function set_club_email($club_email)
	{
		$this->club_email = $club_email;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function add_colors_pic($colors_pic)
	{
		$this->colors[] = $colors_pic;
	}

	public function set_colors($colors)
	{
		$this->colors = $colors;
	}

	public function get_colors()
	{
		return $this->colors;
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
		return ClubsAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_approbation_type() == self::APPROVAL_NOW );
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

	public function get_number_views()
	{
		return $this->number_views;
	}

	public function set_number_views($number_views)
	{
		$this->number_views = $number_views;
	}

	public function get_logo()
	{
		return $this->logo_url;
	}

	public function set_logo(Url $logo)
	{
		$this->logo_url = $logo;
	}

	public function has_logo()
	{
		$logo = $this->logo_url->rel();
		return !empty($logo);
	}

	public function get_logo_mini()
	{
		return $this->logo_mini_url;
	}

	public function set_logo_mini(Url $logo_mini)
	{
		$this->logo_mini_url = $logo_mini;
	}

	public function has_logo_mini()
	{
		$logo_mini = $this->logo_mini_url->rel();
		return !empty($logo_mini);
	}

	public function get_facebook_link()
	{
		if (!$this->facebook_link instanceof Url)
			return new Url('');

		return $this->facebook_link;
	}

	public function set_facebook_link(Url $facebook_link)
	{
		$this->facebook_link = $facebook_link;
	}

	public function get_twitter_link()
	{
		if (!$this->twitter_link instanceof Url)
			return new Url('');

		return $this->twitter_link;
	}

	public function set_twitter_link(Url $twitter_link)
	{
		$this->twitter_link = $twitter_link;
	}

	public function get_gplus_link()
	{
		if (!$this->gplus_link instanceof Url)
			return new Url('');

		return $this->gplus_link;
	}

	public function set_gplus_link(Url $gplus_link)
	{
		$this->gplus_link = $gplus_link;
	}

	public function get_notation()
	{
		return $this->notation;
	}

	public function set_notation(Notation $notation)
	{
		$this->notation = $notation;
	}

	public function is_authorized_to_add()
	{
		return ClubsAuthorizationsService::check_authorizations($this->id_category)->write() || ClubsAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return ClubsAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((ClubsAuthorizationsService::check_authorizations($this->id_category)->write() || (ClubsAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return ClubsAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((ClubsAuthorizationsService::check_authorizations($this->id_category)->write() || (ClubsAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'name' => $this->get_name(),
			'rewrited_name' => $this->get_rewrited_name(),
			'website_url' => $this->get_website_url()->absolute(),
			'district' => $this->get_district(),
			'club_phone' => $this->get_club_phone(),
			'club_email' => $this->get_club_email(),
			'location' => TextHelper::serialize($this->get_location()),
			'stadium_address' => $this->get_stadium_address(),
			'latitude' => $this->get_stadium_latitude(),
			'longitude' => $this->get_stadium_longitude(),
			'facebook_link' => $this->get_facebook_link()->absolute(),
			'twitter_link' => $this->get_twitter_link()->absolute(),
			'gplus_link' => $this->get_gplus_link()->absolute(),
			'contents' => $this->get_contents(),
			'approbation_type' => $this->get_approbation_type(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'number_views' => $this->get_number_views(),
			'logo_url' => $this->get_logo()->relative(),
			'logo_mini_url' => $this->get_logo_mini()->relative(),
			'colors' => TextHelper::serialize($this->get_colors()),
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->name = $properties['name'];
		$this->rewrited_name = $properties['rewrited_name'];
		$this->website_url = new Url($properties['website_url']);
		$this->district = $properties['district'];
		$this->club_phone = $properties['club_phone'];
		$this->club_email = $properties['club_email'];
		$this->location = !empty($properties['location']) ? TextHelper::unserialize($properties['location']) : array();
        $this->stadium_address = $properties['stadium_address'];
        $this->stadium_latitude = $properties['latitude'];
        $this->stadium_longitude = $properties['longitude'];
        $this->facebook_link = new Url($properties['facebook_link']);
        $this->twitter_link = new Url($properties['twitter_link']);
        $this->gplus_link = new Url($properties['gplus_link']);
		$this->contents = $properties['contents'];
		$this->approbation_type = $properties['approbation_type'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->number_views = $properties['number_views'];
		$this->logo_url = new Url($properties['logo_url']);
		$this->logo_mini_url = new Url($properties['logo_mini_url']);
		$this->colors = !empty($properties['colors']) ? TextHelper::unserialize($properties['colors']) : array();

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$notation = new Notation();
		$notation_config = new ClubsNotation();
		$notation->set_module_name('clubs');
		$notation->set_notation_scale($notation_config->get_notation_scale());
		$notation->set_id_in_module($properties['id']);
		$notation->set_number_notes($properties['number_notes']);
		$notation->set_average_notes($properties['average_notes']);
		$notation->set_user_already_noted(!empty($properties['note']));
		$this->notation = $notation;
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->approbation_type = self::APPROVAL_NOW;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->district = 0;
		$this->number_views = 0;
		$this->logo_url = new Url('');
		$this->logo_mini_url = new Url('');
		$this->website_url = new Url('');
		$this->location = array();
        $this->facebook_link = new Url('');
        $this->twitter_link = new Url('');
        $this->gplus_link = new Url('');
		$this->colors = array();
	}

	public function get_array_tpl_vars()
	{
		$category = $this->get_category();
		$contents = FormatingHelper::second_parse($this->contents);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$number_comments = CommentsService::get_number_comments('clubs', $this->id);
        $colors = $this->get_colors();
        $nbr_colors = count($colors);
		$new_content = new ClubsNewContent();
        $config = ClubsConfig::load();
        $default_address = !empty($config->get_default_latitude()) && !empty($config->get_default_longitude());

        if($config->is_gmap_api())
        {
            $googlemaps = GoogleMapsConfig::load();
            $gmap_api_key = $googlemaps->get_api_key();
        } else
            $gmap_api_key = '';

            // Convertisseur degres decimaux -> derges, minutes, secondes
            // Latitude
            $stad_lat = $this->stadium_latitude;

            if($stad_lat > 0)
                $card_lat = 'N';
            else
                $card_lat = 'S';

            $stad_lat = abs($stad_lat);
            $stad_lat_deg = intval($stad_lat);
            $stad_lat_min = ($stad_lat - $stad_lat_deg)*60;
            $stad_lat_sec = ($stad_lat_min - intval($stad_lat))*60;

            // Longitude
            $stad_lng = $this->stadium_longitude;

            if($stad_lng > 0)
                $card_lng = 'E';
            else
                $card_lng = 'W';

            $stad_lng = abs($stad_lng);
            $stad_lng_deg = intval($stad_lng);
            $stad_lng_min = ($stad_lng - $stad_lng_deg)*60;
            $stad_lng_sec = ($stad_lng_min - intval($stad_lng))*60;

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			array(
            'C_NEW_WINDOW' => $config->get_new_window(true),
            'C_GMAP_ENABLED' => $config->is_gmap_api(),
            'C_DEFAULT_ADDRESS' => (!empty($config->get_default_latitude()) && !empty($config->get_default_longitude())) || !empty($config->get_default_address()),
            'C_LOCATION' => !empty($this->location),
            'C_STADIUM_LOCATION' => ($this->stadium_latitude != 0) && ($this->stadium_longitude != 0),
            'C_CONTENTS' => !empty($contents),
			'C_VISIBLE' => $this->is_visible(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
            'C_VISIT' => !empty($this->website_url->absolute()),
			'C_LOGO' => $this->has_logo(),
			'C_LOGO_MINI' => $this->has_logo_mini(),
            'C_DISTRICT' => !empty($this->district),
            'C_PHONE' => !empty($this->club_phone),
            'C_EMAIL' => !empty($this->club_email),
			'C_FACEBOOK' => !empty($this->facebook_link->absolute()),
			'C_TWITTER' => !empty($this->twitter_link->absolute()),
			'C_GPLUS' => !empty($this->gplus_link->absolute()),
			'C_NEW_CONTENT' => $new_content->check_if_is_new_content($this->get_creation_date()->get_timestamp()) && $this->is_visible(),
            'C_COLORS' => $nbr_colors > 0,

            // Deafult values
            'GMAP_API_KEY' => $gmap_api_key,
            'GMAP_API' => $config->is_gmap_api(),
            'DEFAULT_ADDRESS' => $config->get_default_address(),
            'DEFAULT_LATITUDE' => $config->get_default_latitude(),
            'DEFAULT_LONGITUDE' => $config->get_default_longitude(),

			//Clubslink
			'ID' => $this->id,
			'NAME' => $this->name,
			'REWRITED_NAME' => $this->rewrited_name,
			'WEBSITE_URL' => $this->website_url->absolute(),
			'CONTENTS' => $contents,
			'STATUS' => $this->get_status(),
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO' => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,
			'NUMBER_VIEWS' => $this->number_views,
            'DISTRICT' => stripslashes($this->district),
            'LOCATION' => $this->location,
            'PHONE' => $this->club_phone,
            'EMAIL' => $this->club_email,
            'STADIUM_ADDRESS' => $this->stadium_address,
            'LATITUDE' => $this->stadium_latitude,
            'LONGITUDE' => $this->stadium_longitude,
            'STAD_LAT' => str_pad($stad_lat_deg, 2, '0', STR_PAD_LEFT) . '° ' . intval($stad_lat_min) . "' " . number_format($stad_lat_sec, 2) . '" ' . $card_lat,
            'STAD_LNG' => str_pad($stad_lng_deg, 2, '0', STR_PAD_LEFT) . '° ' . intval($stad_lng_min) . "' " . number_format($stad_lng_sec, 2) . '" ' . $card_lng,
			'L_VISITED_TIMES' => StringVars::replace_vars(LangLoader::get_message('visited_times', 'common', 'clubs'), array('number_visits' => $this->number_views)),
			'STATIC_NOTATION' => NotationService::display_static_image($this->get_notation()),
			'NOTATION' => NotationService::display_active_image($this->get_notation()),

			'C_COMMENTS' => !empty($number_comments),
			'L_COMMENTS' => CommentsService::get_lang_comments('clubs', $this->id),
			'NUMBER_COMMENTS' => CommentsService::get_number_comments('clubs', $this->id),

			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image()->rel(),
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? ClubsUrlBuilder::configuration()->rel() : ClubsUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION' => SyndicationUrlBuilder::rss('clubs', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_LINK' => ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_VISIT' => ClubsUrlBuilder::visit($this->id)->rel(),
			'U_DEADLINK' => ClubsUrlBuilder::dead_link($this->id)->rel(),
			'U_CATEGORY' => ClubsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => ClubsUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => ClubsUrlBuilder::delete($this->id)->rel(),
			'U_LOGO' => $this->get_logo()->rel(),
			'U_LOGO_MINI' => $this->get_logo_mini()->rel(),
			'U_FACEBOOK' => $this->facebook_link->absolute(),
			'U_TWITTER' => $this->twitter_link->absolute(),
			'U_GPLUS' => $this->gplus_link->absolute(),
			'U_COMMENTS' => ClubsUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel()
			)
		);

	}
}
?>
