<?php
/*##################################################
 *                      AdminSponsorsDeleteParameterController.class.php
 *                            -------------------
 *   begin                : October 22, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminSponsorsDeleteParameterController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	protected $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $config;

	private $parameter;
	private $id;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);

		if (!$this->get_parameter_items_exists())
		{
			$this->delete_parameter_in_config();
			AppContext::get_response()->redirect(SponsorsUrlBuilder::configuration());
		}

		$this->build_form();
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->form->get_value('delete_parameter_and_content'))
			{
				$this->delete_parameter_and_partners();
			}
			else
			{
				$other_id = $this->form->get_value('move_into_another')->get_raw_value();
				$this->move_into_another($other_id);
			}
			$this->delete_parameter_in_config();
			AppContext::get_response()->redirect(SponsorsUrlBuilder::configuration());
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminSponsorsDisplayResponse($tpl, $this->lang['config.delete_parameter.' . $this->parameter]);
	}

	private function init(HTTPRequestCustom $request)
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->config = SponsorsConfig::load();

		//Get the parameter to delete
		$this->parameter = $request->get_string('parameter', '');
		//Get the id of the parameter to delete
		$this->id = $request->get_int('id', '');

		if (!in_array($this->parameter, array('type', 'activity')) || empty($this->id))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_parameter']);
			$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
			DispatchManager::redirect($controller);
		}

		$types = $this->config->get_types();
		$activities = $this->config->get_activities();

		switch ($this->parameter)
		{
			case 'type':
				if (!isset($types[$this->id]))
				{
					//Error : unexist type
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_type']);
					$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
					DispatchManager::redirect($controller);
				}
				break;
			case 'activity':
				if (!isset($activities[$this->id]))
				{
					//Error : unexist activity
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_activity']);
					$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
					DispatchManager::redirect($controller);
				}
				break;
		}
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('delete_' . $this->parameter, $this->lang['config.delete_parameter.' . $this->parameter]);
		$fieldset->set_description($this->lang['config.delete_parameter.description.' . $this->parameter]);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('delete_parameter_and_content', $this->lang['config.delete_parameter.parameter_and_content.' . $this->parameter], FormFieldCheckbox::UNCHECKED));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('move_into_another', $this->lang['config.delete_parameter.move_into_another'], '', $this->get_move_into_another_options()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_parameter_items_exists()
	{
		return PersistenceContext::get_querier()->row_exists(SponsorsSetup::$sponsors_table, 'WHERE ' . ($this->parameter == 'type' ? 'detected_in=:id_parameter OR fixed_in' : $this->parameter) . '=:id_parameter', array('id_parameter' => $this->id));
	}

	private function get_move_into_another_options()
	{
		$other = array();
		$other[] = new FormFieldSelectChoiceOption(' ', 0);

		$types = $this->config->get_types();
		$activities = $this->config->get_activities();

		switch ($this->parameter)
		{
			case 'type':
				foreach ($types as $key => $type)
				{
					if ($key != $this->id)
						$other[] = new FormFieldSelectChoiceOption(stripslashes($type), $key);
				}
				break;
			case 'activity':
				foreach ($activities as $key => $activity)
				{
					if ($key != $this->id)
						$other[] = new FormFieldSelectChoiceOption(stripslashes($activity), $key);
				}
				break;
		}

		return $other;
	}

	private function delete_parameter_in_config()
	{
		$types = $this->config->get_types();
		$activities = $this->config->get_activities();

		switch ($this->parameter)
		{
			case 'type':
				//Delete the type in the list of types
				unset($types[$this->id]);
				$this->config->set_types($types);
				break;
			case 'activity':
				//Delete the activity in the list of activities
				unset($activities[$this->id]);
				$this->config->set_activities($activities);
				break;
		}

		SponsorsConfig::save();
	}

	private function delete_parameter_and_partners()
	{
		$partners_list = array();
		switch ($this->parameter)
		{
			case 'type':
				$result = PersistenceContext::get_querier()->select_rows(SponsorsSetup::$sponsors_table, array('id'), 'WHERE type=:id', array('id' => $this->id));
				while ($row = $result->fetch())
				{
					$partners_list[] = $row['id'];
				}
				$result->dispose();

				//Delete partners
				SponsorsService::delete('WHERE type=:id', array('id' => $this->id));
				break;
			case 'activity':
				$result = PersistenceContext::get_querier()->select_rows(SponsorsSetup::$sponsors_table, array('id'), 'WHERE activity=:id', array('id' => $this->id));
				while ($row = $result->fetch())
				{
					$partners_list[] = $row['id'];
				}
				$result->dispose();

				//Delete partners
				SponsorsService::delete('WHERE activity=:id', array('id' => $this->id));
				break;
		}
	}

	private function move_into_another($new_id)
	{
		switch ($this->parameter)
		{
			case 'type':
				//Update the type for the partners of this type
				SponsorsService::update_parameter(array('type' => $new_id), 'WHERE type=:id', array('id' => $this->id));
				break;
			case 'activity':
				//Update the activity for the partners of this activity
				SponsorsService::update_parameter(array('activity' => $new_id), 'WHERE activity=:id', array('id' => $this->id));
				break;
		}
	}
}
?>
