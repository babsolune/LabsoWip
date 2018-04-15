<?php
  /* ##################################################
   *                             TeamsportmanagerSetup.class.php
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
    ################################################### */

  /**
   * @author Sebastien LARTIGUE <babsolune@phpboost.com>
   */

  class TeamsportmanagerSetup extends DefaultModuleSetup
  {


      public static $tsm_season;
      public static $tsm_division;
      public static $tsm_club;
      public static $tsm_competition;
      public static $tsm_teams;
      public static $tsm_days;
      public static $tsm_matchs;
      public static $tsm_parameters;
      public static $tsm_ranking;

      public static function __static()
      {
          self::$tsm_season = PREFIX . 'tsm_season';
          self::$tsm_division = PREFIX . 'tsm_division';
          self::$tsm_club = PREFIX . 'tsm_club';
          self::$tsm_competition = PREFIX . 'tsm_competition';
          self::$tsm_teams = PREFIX . 'tsm_teams';
          self::$tsm_days = PREFIX . 'tsm_days';
          self::$tsm_matchs = PREFIX . 'tsm_matchs';
          self::$tsm_parameters = PREFIX . 'tsm_parameters';
          self::$tsm_ranking = PREFIX . 'tsm_ranking';
      }

      public function upgrade($installed_version)
      {
          return '5.1.0';
      }

      public function install()
      {
          $this->drop_tables();
          $this->create_table_season();
          $this->create_table_division();
          $this->create_table_club();
          $this->create_table_competition();
          $this->create_table_teams();
          $this->create_table_days();
          $this->create_table_matchs();
          $this->create_table_parameters();
          $this->create_table_ranking();
      }

      public function uninstall()
      {
          $this->drop_tables();
      }

      private function drop_tables()
      {
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_season);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_division);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_club);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_competition);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_teams);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_days);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_matchs);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_parameters);
          PersistenceContext::get_dbms_utils()->drop(self::$tsm_ranking);
      }

      private function create_table_season()
      {
          $fields_season = array(
                  'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                  'season_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'season_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
          );
          $options_season = array(
                  'primary' => array('id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_season, $fields_season, $options_season);
      }

      private function create_table_division()
      {
          $fields_division = array(
                  'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                  'name' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
                  'type' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'match_type' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
          );
          $options_division = array(
                  'primary' => array('id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_division, $fields_division, $options_division);
      }

      private function create_table_club()
      {
          $fields_club = array(
                  'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                  'name' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
                  'logo_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                  'website_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
          );
          $options_club = array(
                  'primary' => array('id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_club, $fields_club, $options_club);
      }

      private function create_table_competition()
      {
          $fields_competition = array(
                  'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                  'division_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'season_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'rewrited_name' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
				  'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
				  'is_sub_compet' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'master' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'sub_rank' => array('type' => 'text', 'length' => 11),
          );
          $options_competition = array(
                  'primary' => array('id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_competition, $fields_competition, $options_competition);
      }

      private function create_table_teams()
      {
          $fields_teams = array(
                  'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                  'season_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'compet_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'club_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'team_name_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'penalty' => array('type' => 'text', 'length' => 11),
          );
          $options_teams = array(
                  'primary' => array('id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_teams, $fields_teams, $options_teams);
      }

      private function create_table_days()
      {
          $fields_days = array(
                  'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                  'compet_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'day_leg' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'planned_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'matches_number' => array('type' => 'text', 'length' => 11),
          );
          $options_days = array(
                  'primary' => array('id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_days, $fields_days, $options_days);
      }

      private function create_table_matchs()
      {
          $fields_matchs = array(
                  'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                  'day_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'real_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'home_team_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'visit_team_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'home_score' => array('type' => 'text', 'length' => 11),
                  'visit_score' => array('type' => 'text', 'length' => 11),
                  'home_set' => array('type' => 'text', 'length' => 11),
                  'visit_set' => array('type' => 'text', 'length' => 11),
          );
          $options_matchs = array(
                  'primary' => array('id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_matchs, $fields_matchs, $options_matchs);
      }

      private function create_table_parameters()
      {
          $fields_parameters = array(
                  'compet_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  'favourite_team_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  // championship
                  'victory_points' => array('type' => 'text', 'length' => 11),
                  'draw_points' => array('type' => 'text', 'length' => 11),
                  'loss_points' => array('type' => 'text', 'length' => 11),
                  'promotion' => array('type' => 'text', 'length' => 11),
                  'promotion_color' => array('type' => 'string', 'length' => 11, 'default' => "'baffb0'"),
                  'play_off' => array('type' => 'text', 'length' => 11),
                  'play_off_color' => array('type' => 'string', 'length' => 11, 'default' => "'b0e1ff'"),
                  'relegation' => array('type' => 'text', 'length' => 11),
                  'relegation_color' => array('type' => 'string', 'length' => 11, 'default' => "'ffb0b0'"),
                  'has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'ranking_type' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  // cup
                  'round_number' => array('type' => 'text', 'length' => 11),
                  'third_place_match' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'golden_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'silver_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'overtime' => array('type' => 'text', 'length' => 11),
                  // if match with sets (tennis, volley, etc)
                  'set_mode' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'set_number' => array('type' => 'text', 'length' => 11),
          );
          $options_parameters = array(
                  'primary' => array('compet_id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_parameters, $fields_parameters, $options_parameters);
      }

      private function create_table_ranking()
      {
          $fields_ranking = array(
                  'compet_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                  // championship
                  'points' => array('type' => 'text', 'length' => 11),
                  'played' => array('type' => 'text', 'length' => 11),
                  'victory' => array('type' => 'text', 'length' => 11),
                  'draw' => array('type' => 'text', 'length' => 11),
                  'lost' => array('type' => 'text', 'length' => 11),
                  'goals_for' => array('type' => 'text', 'length' => 11),
                  'goals_against' => array('type' => 'text', 'length' => 11),
                  'sets_for' => array('type' => 'text', 'length' => 11),
                  'sets_against' => array('type' => 'text', 'length' => 11),
                  'gen_ga' => array('type' => 'text', 'length' => 11),
                  'part_ga' => array('type' => 'text', 'length' => 11),
                  'penalties' => array('type' => 'text', 'length' => 11),
                  'has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'off_bonus' => array('type' => 'text', 'length' => 11),
                  'def_bonus' => array('type' => 'text', 'length' => 11),
                  'home_points' => array('type' => 'text', 'length' => 11),
                  'home_played' => array('type' => 'text', 'length' => 11),
                  'home_victory' => array('type' => 'text', 'length' => 11),
                  'home_draw' => array('type' => 'text', 'length' => 11),
                  'home_lost' => array('type' => 'text', 'length' => 11),
                  'home_goals_for' => array('type' => 'text', 'length' => 11),
                  'home_goals_against' => array('type' => 'text', 'length' => 11),
                  'home_sets_for' => array('type' => 'text', 'length' => 11),
                  'home_sets_against' => array('type' => 'text', 'length' => 11),
                  'home_gen_ga' => array('type' => 'text', 'length' => 11),
                  'home_part_ga' => array('type' => 'text', 'length' => 11),
                  'home_penalties' => array('type' => 'text', 'length' => 11),
                  'home_has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'home_off_bonus' => array('type' => 'text', 'length' => 11),
                  'home_def_bonus' => array('type' => 'text', 'length' => 11),
                  'visit_points' => array('type' => 'text', 'length' => 11),
                  'visit_played' => array('type' => 'text', 'length' => 11),
                  'visit_victory' => array('type' => 'text', 'length' => 11),
                  'visit_draw' => array('type' => 'text', 'length' => 11),
                  'visit_lost' => array('type' => 'text', 'length' => 11),
                  'visit_goals_for' => array('type' => 'text', 'length' => 11),
                  'visit_goals_against' => array('type' => 'text', 'length' => 11),
                  'visit_sets_for' => array('type' => 'text', 'length' => 11),
                  'visit_sets_against' => array('type' => 'text', 'length' => 11),
                  'visit_gen_ga' => array('type' => 'text', 'length' => 11),
                  'visit_part_ga' => array('type' => 'text', 'length' => 11),
                  'visit_penalties' => array('type' => 'text', 'length' => 11),
                  'visit_has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'visit_off_bonus' => array('type' => 'text', 'length' => 11),
                  'visit_def_bonus' => array('type' => 'text', 'length' => 11),
                  // tour
                  'round_number' => array('type' => 'text', 'length' => 11),
                  'third_place_match' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'golden_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'silver_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                  'overtime' => array('type' => 'text', 'length' => 11),
          );
          $options_ranking = array(
                  'primary' => array('compet_id'),
          );
          PersistenceContext::get_dbms_utils()->create_table(self::$tsm_ranking, $fields_ranking, $options_ranking);
      }
  }
?>
