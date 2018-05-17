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
	private $author_user;
	private $compet_type;
	private $match_type;
	private $is_sub_compet;
	private $compet_master;
	private $sub_rank;
	private $views_nb;

	private $is_published;

    const NOT_PUBLISHED = 0;
    const PUBLISHED = 1;

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

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
		return $this->author_user;
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

	public function set_compet_type($compet_type)
	{
		$this->compet_type = $compet_type;
	}

	public function get_compet_type()
	{
		return $this->compet_type;
	}

	public function set_match_type($match_type)
	{
		$this->match_type = $match_type;
	}

	public function get_match_type()
	{
		return $this->match_type;
	}

	public function subservient()
	{
		$this->enslavement = true;
	}

	public function not_subservient()
	{
		$this->enslavement = false;
	}

	public function is_sub_compet()
	{
		return $this->enslavement;
	}

	public function set_compet_master($compet_master)
	{
		$this->compet_master = $compet_master;
	}

	public function get_compet_master()
	{
		return $this->compet_master;
	}

	public function set_sub_rank($sub_rank)
	{
		$this->sub_rank = $sub_rank;
	}

	public function get_sub_rank()
	{
		return $this->sub_rank;
	}

	public function set_views_nb($views_nb)
	{
		$this->views_nb = $views_nb;
	}

	public function get_views_nb()
	{
		return $this->views_nb;
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
				return LangLoader::get_message('tsm.published', 'common', 'tsm');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('tsm.not.published', 'common', 'tsm');
			break;
		}
	}

	public function is_authorized_to_add()
	{
		return TsmCompetitionsAuthService::check_competition_auth($this->id)->moderation_competition() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_edit()
	{
		return TsmCompetitionsAuthService::check_competition_auth($this->id)->moderation_competition() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_delete()
	{
        return TsmCompetitionsAuthService::check_competition_auth($this->id)->moderation_competition() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
    }

	public function get_properties()
	{
		return array(
			'id'             => $this->get_id(),
			'season_id'      => $this->get_season()->get_id(),
			'division_id'    => $this->get_division()->get_id(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'thumbnail_url'  => $this->get_thumbnail()->relative(),
			'compet_type'    => $this->get_compet_type(),
			'match_type'     => $this->get_match_type(),
			'enslavement'    => (int)$this->is_sub_compet(),
			'compet_master'  => $this->get_compet_master(),
			'sub_rank'       => $this->get_sub_rank(),
			'views_nb'   	 => $this->get_views_nb(),
			'publication'    => (int)$this->is_published(),
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_thumbnail(new Url($properties['thumbnail_url']));
		$this->set_compet_type($properties['compet_type']);
		$this->set_match_type($properties['match_type']);
		$this->set_compet_master($properties['compet_master']);
		$this->set_sub_rank($properties['sub_rank']);
		$this->set_views_nb($properties['views_nb']);

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$season = new Season();
		// $season->set_properties($properties);
		$this->set_season($season);

		$division = new Division();
		// $division->set_properties($properties);
		$this->set_division($division);

		if ($properties['publication'])
			$this->published();
		else
			$this->not_published();

		if ($properties['enslavement'])
			$this->subservient();
		else
			$this->not_subservient();
	}

	public function init_default_properties()
	{
		$this->thumbnail_url = new Url(self::DEFAULT_PICTURE);
		$this->views_nb = 0;
		$this->not_subservient();
		$this->author_user = AppContext::get_current_user();

		if (TsmCompetitionsAuthService::check_competition_auth()->write_competition())
			$this->published();
		else
			$this->not_published();
	}

	public function get_array_tpl_vars()
	{
		$season_id = $this->get_season()->get_id();
		$season_name = $this->get_season()->get_name();
		$rewrited_name = $this->get_division()->get_rewrited_name();
		$name = $this->get_division()->get_name();

		return array(
			//Conditions
			'C_HAS_THUMBNAIL' => $this->has_thumbnail(),

			//Items
			'ID'            => $this->get_id(),
			'NAME'          => $this->get_division()->get_name(),
			'SEASON_NAME'   => $season_name,
			// 'SEASON_DAY'    => $this->get_season()->get_season_date()->get_day(),
			// 'SEASON_MONTH'  => $this->get_season()->get_season_date()->get_month(),
			'VIEWS_NB'      => $this->get_views_nb(),
			'AUTHOR'        => $this->get_author_user()->get_display_name(),

			//Links
			'U_COMPETITION' => TsmUrlBuilder::display_competition($season_id, $season_name, $this->get_id(), $rewrited_name)->rel(),
			'U_THUMBNAIL'   => $this->get_thumbnail()->rel(),
			'U_EDIT_SEASON' => TsmUrlBuilder::edit_season($season_id, $season_name)->rel(),
			'U_TEAMS'       => TsmUrlBuilder::edit_competition_teams($this->get_id())->rel(),
			'U_DAYS'        => TsmUrlBuilder::edit_competition_days($this->get_id())->rel(),
			'U_MATCHES'     => TsmUrlBuilder::edit_competition_matches($this->get_id())->rel(),
			'U_RESULTS'     => TsmUrlBuilder::edit_competition_results($this->get_id())->rel(),
			'U_PARAMS'      => TsmUrlBuilder::edit_competition_params($this->get_id())->rel()
		);
	}
}
?>
