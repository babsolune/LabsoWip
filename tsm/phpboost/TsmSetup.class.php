<?php
    /* ##################################################
    *                             TsmSetup.class.php
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

    class TsmSetup extends DefaultModuleSetup
    {
        public static $tsm_season;
        public static $tsm_division;
        public static $tsm_club;
        public static $tsm_competition;
        public static $tsm_teams;
        public static $tsm_days;
        public static $tsm_matches;
        public static $tsm_parameters;
        public static $tsm_standings;

        public static function __static()
        {
            self::$tsm_season      = PREFIX . 'tsm_season';
            self::$tsm_division    = PREFIX . 'tsm_division';
            self::$tsm_club        = PREFIX . 'tsm_club';
            self::$tsm_competition = PREFIX . 'tsm_competition';
            self::$tsm_teams       = PREFIX . 'tsm_teams';
            self::$tsm_days        = PREFIX . 'tsm_days';
            self::$tsm_matches      = PREFIX . 'tsm_matches';
            self::$tsm_parameters  = PREFIX . 'tsm_parameters';
            self::$tsm_standings   = PREFIX . 'tsm_standings';
        }

        public function upgrade($installed_version)
        {
            return '5.1.0';
        }

        public function install()
        {
            $this->drop_tables();
            $this->create_tables();
            $this->insert_datas();
        }

        public function insert_datas()
        {
            $this->insert_season_datas();
            $this->insert_division_datas();
            $this->insert_club_datas();
        }

        public function uninstall()
        {
            $this->drop_tables();
            ConfigManager::delete('tsm', 'config');
        }

        private function drop_tables()
        {
            PersistenceContext::get_dbms_utils()->drop(
                array(
                    self::$tsm_season,
                    self::$tsm_division,
                    self::$tsm_club,
                    self::$tsm_competition,
                    self::$tsm_teams,
                    self::$tsm_days,
                    self::$tsm_matches,
                    self::$tsm_parameters,
                    self::$tsm_standings
                ));
        }

        private function create_tables()
        {
            $this->_season();
            $this->_division();
            $this->_club();
            $this->_competition();
            $this->_teams();
            $this->_days();
            $this->_matches();
            $this->_parameters();
            $this->_standings();
        }

        private function _season()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'season_type' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'season_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'publication' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_season, $fields, $options);
        }

        private function insert_season_datas()
        {
    		PersistenceContext::get_querier()->insert(self::$tsm_season, array(
                'id' => 1,
                'author_user_id' => 1,
                'season_type' =>0,
                'season_date' => 1525557600,
                'publication' => 1,
            ));
        }

        private function _division()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'rewrited_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'publication' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_division, $fields, $options);
        }

        private function insert_division_datas()
        {
    		$lang = LangLoader::get('division', 'tsm');

    		PersistenceContext::get_querier()->insert(self::$tsm_division, array(
                'id' => 1,
                'author_user_id' => 1,
                'name' => $lang['default.division.name'],
                'rewrited_name' => Url::encode_rewrite($lang['default.division.name']),
                'publication' => 1,
            ));
        }

        private function _club()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'rewrited_name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
                'logo_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'logo_mini_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'colors' => array('type' => 'string', 'length' => 65000),
                'location' => array('type' => 'string', 'length' => 65000),
                'stadium_address' => array('type' => 'string', 'length' => 65000),
                'latitude' => array('type' => 'decimal', 'length' => 18, 'scale' => 15, 'notnull' => 1, 'default' => 0),
                'longitude' => array('type' => 'decimal', 'length' => 18, 'scale' => 15, 'notnull' => 1, 'default' => 0),
                'contact' => array('type' => 'string', 'length' => 65000),
                'website_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'visit_nb' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'facebook_link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'twitter_link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'gplus_link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'publication' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_club, $fields, $options);
        }

        private function insert_club_datas()
        {
    		$lang = LangLoader::get('club', 'tsm');

    		PersistenceContext::get_querier()->insert(self::$tsm_club, array(
                'id' => 1,
                'author_user_id' => 1,
                'name' => $lang['default.club.name'],
                'rewrited_name' => Url::encode_rewrite($lang['default.club.name']),
                'visit_nb' => 0,
                'publication' => 1,
            ));
        }

        private function _competition()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'division_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
                'season_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
                'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
                'compet_type' => array('type' => 'string', 'length' => 127, 'notnull' => 1),
                'match_type' => array('type' => 'string', 'length' => 127, 'notnull' => 1),
                'is_sub_compet' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'master' => array('type' => 'string', 'length' => 11, 'default' => "''"),
                'sub_rank' => array('type' => 'string', 'length' => 11, 'default' => "''")
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_competition, $fields, $options);
        }

        private function _teams()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'season_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'compet_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'club_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'team_name_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'penalty' => array('type' => 'string', 'length' => 11)
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_teams, $fields, $options);
        }

        private function _days()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'compet_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'day_leg' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                'planned_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'matches_number' => array('type' => 'string', 'length' => 11)
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_days, $fields, $options);
        }

        private function _matches()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'day_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'real_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'home_team_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'visit_team_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                'home_score' => array('type' => 'string', 'length' => 11),
                'visit_score' => array('type' => 'string', 'length' => 11),
                'home_set' => array('type' => 'string', 'length' => 11),
                'visit_set' => array('type' => 'string', 'length' => 11)
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_matches, $fields, $options);
        }

        private function _parameters()
        {
            $fields = array(
                'compet_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                'favourite_team_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                // championship
                'victory_points' => array('type' => 'string', 'length' => 11),
                'draw_points' => array('type' => 'string', 'length' => 11),
                'loss_points' => array('type' => 'string', 'length' => 11),
                'promotion' => array('type' => 'string', 'length' => 11),
                'promotion_color' => array('type' => 'string', 'length' => 11, 'default' => "'baffb0'"),
                'play_off' => array('type' => 'string', 'length' => 11),
                'play_off_color' => array('type' => 'string', 'length' => 11, 'default' => "'b0e1ff'"),
                'relegation' => array('type' => 'string', 'length' => 11),
                'relegation_color' => array('type' => 'string', 'length' => 11, 'default' => "'ffb0b0'"),
                'has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
                'standings_type' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
                // cup
                'round_number' => array('type' => 'string', 'length' => 11),
                'third_place_match' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'golden_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'silver_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'overtime' => array('type' => 'string', 'length' => 11),
                // if match with sets (tennis, volley, etc)
                'set_mode' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'set_number' => array('type' => 'string', 'length' => 11)
            );
            $options = array(
                'primary' => array('compet_id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_parameters, $fields, $options);
        }

        private function _standings()
        {
            $fields = array(
                'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
                // championship
                'points' => array('type' => 'string', 'length' => 11),
                'played' => array('type' => 'string', 'length' => 11),
                'victory' => array('type' => 'string', 'length' => 11),
                'draw' => array('type' => 'string', 'length' => 11),
                'lost' => array('type' => 'string', 'length' => 11),
                'goals_for' => array('type' => 'string', 'length' => 11),
                'goals_against' => array('type' => 'string', 'length' => 11),
                'sets_for' => array('type' => 'string', 'length' => 11),
                'sets_against' => array('type' => 'string', 'length' => 11),
                'gen_ga' => array('type' => 'string', 'length' => 11),
                'part_ga' => array('type' => 'string', 'length' => 11),
                'penalties' => array('type' => 'string', 'length' => 11),
                'has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'off_bonus' => array('type' => 'string', 'length' => 11),
                'def_bonus' => array('type' => 'string', 'length' => 11),
                'home_points' => array('type' => 'string', 'length' => 11),
                'home_played' => array('type' => 'string', 'length' => 11),
                'home_victory' => array('type' => 'string', 'length' => 11),
                'home_draw' => array('type' => 'string', 'length' => 11),
                'home_lost' => array('type' => 'string', 'length' => 11),
                'home_goals_for' => array('type' => 'string', 'length' => 11),
                'home_goals_against' => array('type' => 'string', 'length' => 11),
                'home_sets_for' => array('type' => 'string', 'length' => 11),
                'home_sets_against' => array('type' => 'string', 'length' => 11),
                'home_gen_ga' => array('type' => 'string', 'length' => 11),
                'home_part_ga' => array('type' => 'string', 'length' => 11),
                'home_penalties' => array('type' => 'string', 'length' => 11),
                'home_has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'home_off_bonus' => array('type' => 'string', 'length' => 11),
                'home_def_bonus' => array('type' => 'string', 'length' => 11),
                'visit_points' => array('type' => 'string', 'length' => 11),
                'visit_played' => array('type' => 'string', 'length' => 11),
                'visit_victory' => array('type' => 'string', 'length' => 11),
                'visit_draw' => array('type' => 'string', 'length' => 11),
                'visit_lost' => array('type' => 'string', 'length' => 11),
                'visit_goals_for' => array('type' => 'string', 'length' => 11),
                'visit_goals_against' => array('type' => 'string', 'length' => 11),
                'visit_sets_for' => array('type' => 'string', 'length' => 11),
                'visit_sets_against' => array('type' => 'string', 'length' => 11),
                'visit_gen_ga' => array('type' => 'string', 'length' => 11),
                'visit_part_ga' => array('type' => 'string', 'length' => 11),
                'visit_penalties' => array('type' => 'string', 'length' => 11),
                'visit_has_bonus' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'visit_off_bonus' => array('type' => 'string', 'length' => 11),
                'visit_def_bonus' => array('type' => 'string', 'length' => 11),
                // tour
                'round_number' => array('type' => 'string', 'length' => 11),
                'third_place_match' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'golden_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'silver_goal' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'overtime' => array('type' => 'string', 'length' => 11),
                'tour' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'tour_toss' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'tour_champ' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
                'tour_champ_toss' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
            );
            $options = array(
                'primary' => array('id'),
            );
            PersistenceContext::get_dbms_utils()->create_table(self::$tsm_standings, $fields, $options);
        }
    }
?>
