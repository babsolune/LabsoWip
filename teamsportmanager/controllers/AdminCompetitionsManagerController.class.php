<?php
/*##################################################
 *                   AdminCompetitionsManagerController.class.php
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

class AdminCompetitionsManagerController extends AdminModuleController
{
	private $admin_lang,
			$tpl;

	private $season,
			$season_form,
			$season_submit_button;

	private $division,
			$division_form,
			$division_submit_button;

	private $competition,
			$competition_form,
			$competition_submit_button;

	private $club,
			$club_form,
			$club_submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_season_form($request);
		$this->build_division_form($request);
		$this->build_competition_form($request);
		$this->build_club_form($request);

		if ($this->season_submit_button->has_been_submited() && $this->season_form->validate())
		{
			$this->save_season();
			$this->season_form->get_field_by_id('season_end_date')->set_hidden(!$this->get_season()->has_season_end_date());
			$this->tpl->put('SEASON_MSG', MessageHelper::display($this->admin_lang['admin.create.season.success'], MessageHelper::SUCCESS, 5));
		}

		if ($this->division_submit_button->has_been_submited() && $this->division_form->validate())
		{
			$this->save_division();
			$this->tpl->put('DIVISION_MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		if ($this->competition_submit_button->has_been_submited() && $this->competition_form->validate())
		{
			$this->save_competition();
			$this->tpl->put('COMPETITION_MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		if ($this->club_submit_button->has_been_submited() && $this->club_form->validate())
		{
			$this->save_club();
			$this->tpl->put('CLUB_MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$this->tpl->put_all(array(
			'SEASON_FORM'      => $this->season_form->display(),
			'DIVISION_FORM'    => $this->division_form->display(),
			'COMPETITION_FORM' => $this->competition_form->display(),
			'CLUB_FORM'        => $this->club_form->display()
		));

		return new AdminTeamsportmanagerDisplayResponse($this->tpl, $this->admin_lang['admin.competitions.manager']);
	}

	private function init()
	{
		$this->tpl = new FileTemplate('teamsportmanager/AdminCompetitionsManagerController.tpl');
		$this->admin_lang = LangLoader::get('admin', 'teamsportmanager');
		$this->tpl->add_lang($this->admin_lang);
	}

	private function build_season_form(HTTPRequestCustom $request)
	{
		$season_form = new HTMLForm(__CLASS__);

		//Configuration
		$season_fieldset = new FormFieldsetHTML('add_season', $this->admin_lang['admin.create.season']);
		$season_form->add_fieldset($season_fieldset);

		$season_fieldset->add_field($season_start_date = new FormFieldDate('season_start_date', $this->admin_lang['admin.season.start.date'], $this->get_season()->get_season_start_date(), array('required' => true)));

		$season_fieldset->add_field(new FormFieldCheckbox('season_end_date_enable', $this->admin_lang['admin.season.end.date.enable'], $this->get_season()->has_season_end_date(),
			array('events' => array('click' => '
					if (HTMLForms.getField("season_end_date_enable").getValue()) {
						//HTMLForms.getField("season_end_date").enable();
					} else {
						//HTMLForms.getField("season_end_date").disable();
					}'
			))
		));

		$season_fieldset->add_field($season_end_date = new FormFieldDate('season_end_date', $this->admin_lang['admin.season.end.date'], $this->get_season()->get_season_end_date(),
			array('hidden' => !$this->get_season()->has_season_end_date())
		));

		if($this->get_season()->has_season_end_date())
			$season_form->add_constraint(new FormConstraintFieldsDifferenceSuperior($season_start_date, $season_end_date));


		$this->season_submit_button = new FormButtonDefaultSubmit();
		$season_form->add_button($this->season_submit_button);
		$season_form->add_button(new FormButtonReset());

		$this->season_form = $season_form;
	}

	private function save_season()
	{
		$season = $this->get_season();

		$season->set_season_start_date($this->season_form->get_value('season_start_date'));

		if($this->get_season()->has_season_end_date() == true)
			$season->set_season_end_date($this->season_form->get_value('season_end_date'));
		else
			$season->clean_season_end_date();

	}

	private function build_division_form(HTTPRequestCustom $request)
	{
		$division_form = new HTMLForm(__CLASS__);

		//Configuration
		$division_fieldset = new FormFieldsetHTML('add_division', $this->admin_lang['admin.create.division']);
		$division_form->add_fieldset($division_fieldset);

		$this->division_submit_button = new FormButtonDefaultSubmit();
		$division_form->add_button($this->division_submit_button);
		$division_form->add_button(new FormButtonReset());

		$this->division_form = $division_form;

	}

	private function save_division()
	{

	}

	private function build_competition_form(HTTPRequestCustom $request)
	{
		$competition_form = new HTMLForm(__CLASS__);

		//Configuration
		$competition_fieldset = new FormFieldsetHTML('add_competition', $this->admin_lang['admin.create.competition']);
		$competition_form->add_fieldset($competition_fieldset);

		$this->competition_submit_button = new FormButtonDefaultSubmit();
		$competition_form->add_button($this->competition_submit_button);
		$competition_form->add_button(new FormButtonReset());

		$this->competition_form = $competition_form;

	}

	private function save_competition()
	{

	}

	private function build_club_form(HTTPRequestCustom $request)
	{
		$club_form = new HTMLForm(__CLASS__);

		//Configuration
		$club_fieldset = new FormFieldsetHTML('add_club', $this->admin_lang['admin.create.club']);
		$club_form->add_fieldset($club_fieldset);

		$this->club_submit_button = new FormButtonDefaultSubmit();
		$club_form->add_button($this->club_submit_button);
		$club_form->add_button(new FormButtonReset());

		$this->club_form = $club_form;

	}

	private function save_club()
	{

	}

	private function get_season()
	{
		if ($this->season === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->season = Season::get_season('WHERE seasons.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_season = true;
				$this->season = new Season();
				$this->season->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->season;
	}
}
?>
