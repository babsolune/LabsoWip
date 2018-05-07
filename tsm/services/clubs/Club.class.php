<?php
/*##################################################
 *                        Club.class.php
 *                            -------------------
 *   begin                : February 13, 2018
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

class Club
{
    private $id;
    private $name;
    private $rewrited_name;
    private $logo_url;
    private $logo_mini_url;
    private $website_url;
    private $visit_nb;
	private $author_user;
    private $colors;
    private $contact;
    private $facebook_link;
    private $twitter_link;
    private $gplus_link;
    private $location;
    private $stadium_address;
    private $stadium_latitude;
    private $stadium_longitude;

	private $is_published;

    const NOT_PUBLISHED = 0;
    const PUBLISHED = 1;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
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

	public function set_logo(Url $logo)
	{
		$this->logo_url = $logo;
	}

	public function get_logo()
	{
		return $this->logo_url;
	}

	public function has_logo()
	{
		$logo = $this->logo_url->rel();
		return !empty($logo);
	}

	public function set_logo_mini(Url $logo_mini)
	{
		$this->logo_mini_url = $logo_mini;
	}

	public function get_logo_mini()
	{
		return $this->logo_mini_url;
	}

	public function has_logo_mini()
	{
		$logo_mini = $this->logo_mini_url->rel();
		return !empty($logo_mini);
	}

	public function set_website(Url $website)
	{
		$this->website_url = $website;
	}

	public function get_website()
	{
		return $this->website_url;
	}

	public function has_website()
	{
		$website = $this->website_url->rel();
		return !empty($website);
	}

	public function set_visit_nb($visit_nb)
	{
		$this->visit_nb = $visit_nb;
	}

	public function get_visit_nb()
	{
		return $this->visit_nb;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function colors_picker($colors_picker)
	{
		$this->colors[] = $colors_picker;
	}

	public function set_colors($colors)
	{
		$this->colors = $colors;
	}

	public function get_colors()
	{
		return $this->colors;
	}

	public function contact_form($contact_form)
	{
		$this->contact[] = $contact_form;
	}

	public function set_contact($contact)
	{
		$this->contact = $contact;
	}

	public function get_contact()
	{
		return $this->contact;
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

	public function get_status()
	{
		switch ($this->is_published()) {
			case self::PUBLISHED:
				return LangLoader::get_message('clubs.published', 'club', 'tsm');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('clubs.not.published', 'club', 'tsm');
			break;
		}
	}

	public function is_authorized_to_add()
	{
		return TsmClubsAuthService::check_club_auth($this->id)->moderation_club() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_edit()
	{
		return TsmClubsAuthService::check_club_auth($this->id)->moderation_club() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_delete()
	{
        return TsmClubsAuthService::check_club_auth($this->id)->moderation_club() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
    }

    public function get_properties()
    {
        return array(
            'id'              => $this->get_id(),
            'name'            => $this->get_name(),
            'rewrited_name'   => $this->get_rewrited_name(),
			'logo_url'        => $this->get_logo()->relative(),
			'logo_mini_url'   => $this->get_logo_mini()->relative(),
			'website_url'     => $this->get_website()->relative(),
			'visit_nb'        => $this->get_visit_nb(),
			'author_user_id'  => $this->get_author_user()->get_id(),
			'publication'     => (int)$this->is_published(),
			'colors'          => TextHelper::serialize($this->get_colors()),
			'contact'         => TextHelper::serialize($this->get_contact()),
			'facebook_link'   => $this->get_facebook_link()->absolute(),
			'twitter_link'    => $this->get_twitter_link()->absolute(),
			'gplus_link'      => $this->get_gplus_link()->absolute(),
			'location'        => TextHelper::serialize($this->get_location()),
			'stadium_address' => $this->get_stadium_address(),
			'latitude'        => $this->get_stadium_latitude(),
			'longitude'       => $this->get_stadium_longitude(),
        );
    }

    public function set_properties(array $properties)
    {
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_rewrited_name($properties['rewrited_name']);
		$this->set_logo(new Url($properties['logo_url']));
		$this->set_logo_mini(new Url($properties['logo_mini_url']));
		$this->set_website(new Url($properties['website_url']));
		$this->set_visit_nb($properties['visit_nb']);
        $this->colors = !empty($properties['colors']) ? TextHelper::unserialize($properties['colors']) : array();
        $this->contact = !empty($properties['contact']) ? TextHelper::unserialize($properties['contact']) : array();
        $this->facebook_link = new Url($properties['facebook_link']);
        $this->twitter_link = new Url($properties['twitter_link']);
        $this->gplus_link = new Url($properties['gplus_link']);
		$this->location = !empty($properties['location']) ? TextHelper::unserialize($properties['location']) : array();
        $this->stadium_address = $properties['stadium_address'];
        $this->stadium_latitude = $properties['latitude'];
        $this->stadium_longitude = $properties['longitude'];

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

    public function init_default_properties()
	{
        $this->logo_url = new Url('');
        $this->logo_mini_url = new Url('');
        $this->website_url = new Url('');
        $this->visit_nb = 0;
		$this->author_user = AppContext::get_current_user();
		$this->colors = array();
		$this->contact = array();
        $this->facebook_link = new Url('');
        $this->twitter_link = new Url('');
        $this->gplus_link = new Url('');
		$this->location = array();

		if (TsmClubsAuthService::check_club_auth()->write_club())
			$this->published();
		else
			$this->not_published();
    }

	public function get_array_tpl_vars()
	{
        $this->config = TsmConfig::load();

        // Convertisseur degres decimaux -> degres, minutes, secondes
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

        return array(
            'C_MODERATE' => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
            'C_HAS_LOGO'    => $this->has_logo(),
            'C_HAS_WEBSITE' => $this->has_website(),
            'C_HAS_GMAP' => !empty($this->config->get_default_address()),

            'DEFAULT_LOGO' =>  TPL_PATH_TO_ROOT . '/tsm/templates/default.png',
            'DEFAULT_LOGO_MINI' => TPL_PATH_TO_ROOT . '/tsm/tsm.png',

            'ID'            => $this->get_id(),
            'NAME'          => $this->get_name(),
            'REWRITED_NAME' => $this->get_rewrited_name(),

            'U_CLUB'        => TsmUrlBuilder::display_club($this->get_id(), $this->get_rewrited_name())->rel(),
			'U_LOGO'        => $this->get_logo()->rel(),
			'U_WEBSITE'     => $this->get_website()->rel(),
            'U_DEADLINK' => TsmUrlBuilder::dead_link_club($this->id)->rel(),
			'U_EDIT' => TsmUrlBuilder::edit_club($this->id)->rel(),
			'U_DELETE' => TsmUrlBuilder::delete_club($this->id)->rel(),
        );
    }
}
?>
