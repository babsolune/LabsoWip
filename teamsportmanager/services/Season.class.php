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
    private $season_start_date;
    private $has_season_end_date;
    private $season_end_date;
    private $seasons_number;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_season_start_date(Date $season_start_date)
	{
		$this->season_start_date = $season_start_date;
	}

	public function get_season_start_date()
	{
		return $this->season_start_date;
	}

	public function set_season_end_date(Date $season_end_date)
	{
		$this->season_end_date = $season_end_date;
        $this->has_season_end_date = true;
	}

	public function get_season_end_date()
	{
		return $this->season_end_date;
	}

	public function has_season_end_date()
	{
		return $this->has_season_end_date;
	}

    public function get_seasons_number()
    {
        return $this->seasons_number;
    }

    public function set_seasons_number($seasons_number)
    {
        $this->seasons_number=$seasons_number;
    }

    public function get_properties()
    {
        return array(
            'id' => $this->get_id(),
            'season_start_date' => $this->get_season_start_date(),
            'season_end_date' => $this->get_season_end_date(),
        );
    }

    public function set_properties(array $properties)
    {
		$this->set_id($properties['id']);
        $this->set_season_start_date($properties['season_start_date']);
        $this->set_season_end_date($properties['season_end_date']);
		$this->enabled_end_date = !empty($properties['season_end_date']);
    }

    public function init_default_properties()
	{
        $this->season_start_date = new Date();
        $this->season_end_date = new Date();
    }

    public function clean_season_end_date()
    {
        $this->season_end_date = null;
        $this->has_season_end_date = false;
    }

	public function get_array_tpl_vars()
	{
        return array_merge(
            Date::get_array_tpl_vars($this->season_start_date, 'season_start_date'),
			Date::get_array_tpl_vars($this->season_end_date, 'season_end_date'),
			array(
                'ID' => $this->get_id(),

                'U_EDIT_SEASON'   => TeamsportmanagerUrlBuilder::edit_season($this->id)->rel(),
                'U_DELETE_SEASON' => TeamsportmanagerUrlBuilder::delete_season($this->id)->rel(),
            )
        );
    }
}
?>
