<?php
/*##################################################
 *                   TsmCompetitionsDaysFormController.class.php
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

class TsmCompetitionsDaysFormController extends AdminModuleController
{
	private $admin_lang,
			$tpl,
			$form,
			$competition;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->build_form();
			$this->tpl->put('COMPETITION_MSG', MessageHelper::display($this->admin_lang['admin.create.season.success'], MessageHelper::SUCCESS, 5));
		}

		$this->tpl->put_all(array(
			'FORM' => $this->form->display()
		));
	}

	private function init()
	{
		$this->tpl = new FileTemplate('tsm/AdminCompetitionsManagerController.tpl');
		$this->admin_lang = LangLoader::get('admin', 'tsm');
		$this->tpl->add_lang($this->admin_lang);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('add_competition', $this->admin_lang['admin.create.competition']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('select_season', $this->admin_lang['admin.select.season'], '', array())); //$this->season_list()

		$fieldset->add_field(new FormFieldSimpleSelectChoice('select_division', $this->admin_lang['admin.select.division'], '', array()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->_form = $form;
	}

	private function get_competition()
	{
		if ($this->competition === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->competition = Competition::get_competition('WHERE tsm_competition.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_competition = true;
				$this->competition = new Competition();
			}
		}
		return $this->competition;
	}

	private function season_list()
	{
		$season_options = array();

		$result = PersistenceContext::get_querier()->select('SELECT seasons.*
		FROM ' . TsmSetup::$tsm_season . ' seasons
		ORDER BY season_start_date DESC');

		while($row = $result->fetch())
		{
			$season_options[] = new FormFieldSelectChoiceOption($row['name'], $row['id']);
		}
		var_dump($result);
	}

	private function save()
	{

	}
}
?>
