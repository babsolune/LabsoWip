<?php
/*##################################################
 *                        Season.class.php
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

class Season
{
    private $id;
    private $name;
	private $is_calendar;
	private $author_user;
    private $season_date;
    private $seasons_number;

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

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function date_season_type()
	{
		$this->season_type = false;
	}

	public function calendar_season_type()
	{
		$this->season_type = true;
	}

    public function is_calendar()
    {
        return $this->season_type;
    }

	public function set_season_date(Date $season_date)
	{
		$this->season_date = $season_date;
	}

	public function get_season_date()
	{
		return $this->season_date;
	}

    public function get_seasons_number()
    {
        return $this->seasons_number;
    }

    public function set_seasons_number($seasons_number)
    {
        $this->seasons_number=$seasons_number;
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
				return LangLoader::get_message('seasons.published', 'season', 'tsm');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('seasons.not.published', 'season', 'tsm');
			break;
		}
	}

	public function is_authorized_to_add()
	{
		return TsmSeasonsAuthService::check_season_auth($this->id)->moderation_season() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_edit()
	{
		return TsmSeasonsAuthService::check_season_auth($this->id)->moderation_season() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_delete()
	{
        return TsmSeasonsAuthService::check_season_auth($this->id)->moderation_season() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
    }

    public function get_properties()
    {
        return array(
            'id'                => $this->get_id(),
            'season_type'       => (int)$this->is_calendar(),
			'author_user_id'    => $this->get_author_user()->get_id(),
            'season_date'       => $this->get_season_date()->get_timestamp(),
			'publication'       => (int)$this->is_published(),
        );
    }

    public function set_properties(array $properties)
    {
		$this->set_id($properties['id']); // ID de la saison

        if($properties['season_type']) // Etat de la checkbox "Annee Calendaire"
            $this->calendar_season_type();
        else
            $this->date_season_type();

        $this->set_season_date(new Date($properties['season_date'], Timezone::SERVER_TIMEZONE)); // annee (de depart) de la saison

        $season_name = $this->get_season_date()->get_year(); // On recupere l'annee du timestamp
        if($this->is_calendar()) // si l'annee est calendaire
            $this->name = $season_name; // le nom de la saison est [annee]
        else
            $this->name = $season_name . '-' . ($season_name + 1); // le nom de la saison est [annee]-[annee+1]

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
        $this->season_type = false;
        $this->season_date = new Date();
		$this->author_user = AppContext::get_current_user();

		if (TsmSeasonsAuthService::check_season_auth()->write_season())
			$this->published();
		else
			$this->not_published();
    }

	public function get_array_tpl_vars()
	{
        return array_merge(
            Date::get_array_tpl_vars($this->season_date, 'season_date'),
            array(
                'C_PUBLISHED' => $this->is_published(),
                'SEASON_NAME' => $this->get_name(),

                'U_SEASON' => TsmUrlBuilder::display_season($this->get_id(), $this->get_name())->rel(),
    			'U_EDIT' => TsmUrlBuilder::edit_season($this->id)->rel(),
    			'U_DELETE' => TsmUrlBuilder::delete_season($this->id)->rel(),
            )
        );
    }
}
?>
