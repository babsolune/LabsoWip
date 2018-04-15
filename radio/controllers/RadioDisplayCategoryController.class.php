<?php
/*##################################################
 *		               RadioDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class RadioDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;

	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'radio');

		$this->tpl = new FileTemplate('radio/RadioDisplaySeveralProgramsController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = RadioConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY radio.release_day ASC, radio.start_date ASC', array_merge($parameters, array(

		)));

		$this->tpl->put_all(array(
			'C_CATEGORY' => true,
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? RadioUrlBuilder::configuration()->rel() : RadioUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
		));
		$result->dispose();

		$this->build_monday_view();
		$this->build_tuesday_view();
		$this->build_wednesday_view();
		$this->build_thursday_view();
		$this->build_friday_view();
		$this->build_saturday_view();
		$this->build_sunday_view();
	}

	private function build_monday_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$monday_tpl = new FileTemplate('radio/week/RadioDisplayMondayProgramsController.tpl');
		$monday_tpl->add_lang($this->lang);

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1 AND release_day = 1';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*,
		time (
		from_unixtime(start_date)) AS hour
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY hour ASC', array_merge($parameters, array(

		)));

		$monday_tpl->put_all(array(
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_NO_PROGRAM_AVAILABLE' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$monday_tpl->assign_block_vars('monday_prg', $radio->get_array_tpl_vars());
		}
		$result->dispose();

		$this->tpl->put('MONDAY_PRG', $monday_tpl);
	}

	private function build_tuesday_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$tuesday_tpl = new FileTemplate('radio/week/RadioDisplayTuesdayProgramsController.tpl');
		$tuesday_tpl->add_lang($this->lang);

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1 AND release_day = 2';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*,
		time (
		from_unixtime(start_date)) AS hour
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY hour ASC', array_merge($parameters, array(

		)));

		$tuesday_tpl->put_all(array(
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_NO_PROGRAM_AVAILABLE' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$tuesday_tpl->assign_block_vars('tuesday_prg', $radio->get_array_tpl_vars());
		}
		$result->dispose();

		$this->tpl->put('TUESDAY_PRG', $tuesday_tpl);
	}

	private function build_wednesday_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$wednesday_tpl = new FileTemplate('radio/week/RadioDisplayWednesdayProgramsController.tpl');
		$wednesday_tpl->add_lang($this->lang);

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1 AND release_day = 3';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*,
		time (
		from_unixtime(start_date)) AS hour
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY hour ASC', array_merge($parameters, array(

		)));

		$wednesday_tpl->put_all(array(
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_NO_PROGRAM_AVAILABLE' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$wednesday_tpl->assign_block_vars('wednesday_prg', $radio->get_array_tpl_vars());
		}
		$result->dispose();

		$this->tpl->put('WEDNESDAY_PRG', $wednesday_tpl);
	}

	private function build_thursday_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$thursday_tpl = new FileTemplate('radio/week/RadioDisplayThursdayProgramsController.tpl');
		$thursday_tpl->add_lang($this->lang);

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1 AND release_day = 4';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*,
		time (
		from_unixtime(start_date)) AS hour
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY hour ASC', array_merge($parameters, array(

		)));

		$thursday_tpl->put_all(array(
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_NO_PROGRAM_AVAILABLE' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$thursday_tpl->assign_block_vars('thursday_prg', $radio->get_array_tpl_vars());
		}
		$result->dispose();

		$this->tpl->put('THURSDAY_PRG', $thursday_tpl);
	}

	private function build_friday_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$friday_tpl = new FileTemplate('radio/week/RadioDisplayFridayProgramsController.tpl');
		$friday_tpl->add_lang($this->lang);

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1 AND release_day = 5';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*,
		time (
		from_unixtime(start_date)) AS hour
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY hour ASC', array_merge($parameters, array(

		)));

		$friday_tpl->put_all(array(
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_NO_PROGRAM_AVAILABLE' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$friday_tpl->assign_block_vars('friday_prg', $radio->get_array_tpl_vars());
		}
		$result->dispose();

		$this->tpl->put('FRIDAY_PRG', $friday_tpl);
	}

	private function build_saturday_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$saturday_tpl = new FileTemplate('radio/week/RadioDisplaySaturdayProgramsController.tpl');
		$saturday_tpl->add_lang($this->lang);

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1 AND release_day = 6';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*,
		time (
		from_unixtime(start_date)) AS hour
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY hour ASC', array_merge($parameters, array(

		)));

		$saturday_tpl->put_all(array(
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_NO_PROGRAM_AVAILABLE' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$saturday_tpl->assign_block_vars('saturday_prg', $radio->get_array_tpl_vars());
		}
		$result->dispose();

		$this->tpl->put('SATURDAY_PRG', $saturday_tpl);
	}

	private function build_sunday_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories($this->get_category()->get_id());
		$radio_config = RadioConfig::load();

		$sunday_tpl = new FileTemplate('radio/week/RadioDisplaySundayProgramsController.tpl');
		$sunday_tpl->add_lang($this->lang);

		$condition = 'WHERE id_category IN :authorized_categories
		AND approbation_type = 1 AND release_day = 7';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*,
		time (
		from_unixtime(start_date)) AS hour
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY hour ASC', array_merge($parameters, array(

		)));

		$sunday_tpl->put_all(array(
			'C_DISPLAY_BLOCK' => $this->config->get_display_type() == RadioConfig::DISPLAY_BLOCK,
			'C_DISPLAY_TABLE' => $this->config->get_display_type() == RadioConfig::DISPLAY_TABLE,
			'C_DISPLAY_CALENDAR' => $this->config->get_display_type() == RadioConfig::DISPLAY_CALENDAR,
			'C_NO_PROGRAM_AVAILABLE' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$sunday_tpl->assign_block_vars('sunday_prg', $radio->get_array_tpl_vars());
		}
		$result->dispose();

		$this->tpl->put('SUNDAY_PRG', $sunday_tpl);
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = RadioService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = RadioService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (!RadioAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!RadioAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['radio']);
		else
			$graphical_environment->set_page_title($this->lang['radio']);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(RadioUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['radio'], RadioUrlBuilder::home());

		$categories = array_reverse(RadioService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), RadioUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->tpl;
	}
}
?>
