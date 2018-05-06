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
	private $season;
	private $division;
	private $thumbnail_url;
	private $views_number;

	const DEFAULT_PICTURE = '/tsm/templates/images/default.png';

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_season(Season $season)
	{
		$this->season = $season;
	}

	public function get_season()
	{
		return $this->season;
	}

	public function set_division(Division $division)
	{
		$this->division = $division;
	}

	public function get_division()
	{
		return $this->division;
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

	public function get_properties()
	{
		return array(
			'id'                     => $this->get_id(),
			'season_id'           	 => $this->get_season()->get_id(),
			'division_id'            => $this->get_division()->get_id(),
			'thumbnail_url'          => $this->get_thumbnail()->relative(),
			'views_number'           => $this->get_views_number(),
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_thumbnail(new Url($properties['thumbnail_url']));

		$season_class = new Season();
		$season_class->set_properties($properties);
		$this->set_season($season_class);

		$division_class = new Division();
		$division_class->set_properties($properties);
		$this->set_division($division_class);
	}

	public function init_default_properties()
	{
		$this->thumbnail_url = new Url(self::DEFAULT_PICTURE);
		$this->views_number = 0;
	}

	public function get_array_tpl_vars()
	{
		$season_name = $this->get_season()->get_season_start_date()->get_year();
		$name = $this->get_division()->get_name();
		$rewrited_name = Url::encode_rewrite($name);

		return array(
			//Conditions
			'C_HAS_THUMBNAIL'    => $this->has_thumbnail(),

			//Items
			'ID'                 => $this->get_id(),
			'NAME'               => $name,
			'VIEWS_NUMBER'       => $this->get_views_number(),

			//Links
			'U_COMPETITION'     => TsmUrlBuilder::display_competition($season_name, $this->get_id(), $rewrited_name)->rel(),
			'U_THUMBNAIL'   	=> $this->get_thumbnail()->rel()
		);
	}
}
?>
