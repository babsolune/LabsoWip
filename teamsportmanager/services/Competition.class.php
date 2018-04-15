<?php
/*##################################################
 *                        Competition.class.php
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

class Competition
{
	private $id;
	private $season_id;
	private $division_id;
	private $name;
	private $rewrited_name;
	private $thumbnail_url;
	private $views_number;

	const DEFAULT_PICTURE = '/teamsportmanager/templates/images/default.png';

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_season_id($season_id)
	{
		$this->season_id = $season_id;
	}

	public function get_season_id()
	{
		return $this->season_id;
	}

	public function get_season()
	{
		return Season::get_id($this->season_id);
	}

	public function set_division_id($division_id)
	{
		$this->division_id = $division_id;
	}

	public function get_division_id()
	{
		return $this->division_id;
	}

	public function get_division()
	{
		return Division::get_id($this->division_id);
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

	public function set_thumbnail(Url $thumbnail)
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function get_thumbnail()
	{
		return $this->thumbnail_url;
	}

	public function has_thumbnail()
	{
		$thumbnail = $this->thumbnail_url->rel();
		return !empty($thumbnail);
	}

	public function set_views_number($views_number)
	{
		$this->views_number = $views_number;
	}

	public function get_views_number()
	{
		return $this->views_number;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function get_updated_date()
	{
		return $this->updated_date;
	}

	public function set_updated_date(Date $updated_date)
	{
	    $this->updated_date = $updated_date;
	}



	public function get_properties()
	{
		return array(
			'id'                     => $this->get_id(),
			'season_id'           	 => $this->get_season_id(),
			'division_id'            => $this->get_division_id(),
			'title'                  => $this->get_title(),
			'rewrited_title'         => $this->get_rewrited_title(),
			'thumbnail_url'          => $this->get_thumbnail()->relative(),
			'views_number'           => $this->get_views_number(),
			'creation_date'          => $this->get_creation_date()->get_timestamp(),
			'updated_date'           => $this->get_updated_date() !== null ? $this->get_updated_date()->get_timestamp() : 0
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_season_id($properties['season_id']);
		$this->set_division_id($properties['division_id']);
		$this->set_title($properties['title']);
		$this->set_rewrited_title($properties['rewrited_title']);
		$this->set_thumbnail(new Url($properties['thumbnail_url']));
		$this->set_creation_date(new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE));
		$this->updated_date = !empty($properties['updated_date']) ? new Date($properties['updated_date'], Timezone::SERVER_TIMEZONE) : null;
	}

	public function init_default_properties($season_id, $division_id)
	{
		$this->season_id = $season_id;
		$this->division_id = $division_id;
		$this->creation_date = new Date();
		$this->thumbnail_url = new Url(self::DEFAULT_PICTURE);
		$this->views_number = 0;
	}

	public function get_array_tpl_vars()
	{
		$season   = $this->get_season();
		$division = $this->get_division();

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			Date::get_array_tpl_vars($this->updated_date, 'updated_date'),
			array(
			//Conditions
			'C_HAS_THUMBNAIL'                  => $this->has_thumbnail(),
			'C_UPDATED_DATE'                   => $this->updated_date != null,

			//Teamsportmanager
			'ID'                 => $this->get_id(),
			'TITLE'              => $this->get_title(),
			'VIEWS_NUMBER'       => $this->get_views_number(),

			//Links
			'U_COMPETITION'     => TeamsportmanagerUrlBuilder::display_competition($season->get_id(), $season->get_season_start_date()->get_year(), $this->get_id(), $this->get_rewrited_title())->rel(),
			'U_THUMBNAIL'   	=> $this->get_thumbnail()->rel()
			)
		);
	}
}
?>
