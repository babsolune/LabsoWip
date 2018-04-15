<?php
/*##################################################
 *                               AdminSponsorsConfigController.class.php
 *                            -------------------
 *   begin                : September 13, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class AdminSponsorsConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $admin_common_lang;

	/**
	 * @var SponsorsConfig
	 */
	private $config;

	private $max_input = 150;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('types_list')->set_value($this->build_types_list()->render());
			$this->form->get_field_by_id('activities_list')->set_value($this->build_activities_list()->render());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminSponsorsDisplayResponse($tpl, $this->lang['module.config.title']);
	}

	private function init()
	{
		$this->config = SponsorsConfig::load();
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldHTML('types_list', $this->build_types_list()->render()));

		$fieldset->add_field(new FormFieldHTML('activities_list', $this->build_activities_list()->render()));

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_number_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_number_per_page', $this->admin_common_lang['config.categories_number_per_page'], $this->config->get_categories_number_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('columns_number_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_columns_number_per_line(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('category_display_type', $this->lang['config.category.display.type'], $this->config->get_category_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['config.category.display.type.display.block'], SponsorsConfig::DISPLAY_BLOCK),
				new FormFieldSelectChoiceOption($this->lang['config.category.display.type.display.table'], SponsorsConfig::DISPLAY_TABLE)
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations'],
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.categories_management'], Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_types_list()
	{
		$types = $this->config->get_types();

		$types_list = new FileTemplate('sponsors/fields/AdminSponsorsTypesListController.tpl');
		$types_list->add_lang($this->lang);

		$key = 0;
		foreach ($types as $key => $type)
		{
			$types_list->assign_block_vars('types', array(
				'ID'			=> $key,
				'NAME'			=> stripslashes($type),
				'LINK_DELETE'	=> SponsorsUrlBuilder::delete_parameter('type', $key)->rel()
			));
		}

		$types_list->put_all(array(
			'C_TYPES'							=> !empty($types),
			'MAX_INPUT'							=> $this->max_input,
		 	'NEXT_ID'							=> $key + 1
		));

		return $types_list;
	}

	private function build_activities_list()
	{
		$activities = $this->config->get_activities();
		$activities_list = new FileTemplate('sponsors/fields/AdminSponsorsActivitiesListController.tpl');

		$key = 0;
		foreach ($activities as $key => $activity)
		{
			$activities_list->assign_block_vars('activities', array(
				'ID'			=> $key,
				'NAME'			=> stripslashes($activity),
				'LINK_DELETE'	=> SponsorsUrlBuilder::delete_parameter('activity', $key)->rel()
			));
		}

		$activities_list->put_all(array(
			'C_ACTIVITIES'						=> !empty($activities),
			'MAX_INPUT'							=> $this->max_input,
		 	'NEXT_ID'							=> $key + 1
		));

		return $activities_list;
	}

	private function save()
	{
		$request = AppContext::get_request();

		$types = $this->config->get_types();
		$activities = $this->config->get_activities();

		foreach ($types as $key => $type)
		{
			$new_type_name = $request->get_value('type' . $key, '');
			$types[$key] = (!empty($new_type_name) && $new_type_name != $type) ? $new_type_name : $type;
		}

		$nb_types = count($types);
		for ($i = 1; $i <= $this->max_input; $i++)
		{
			$type = 'type_' . $i;
			if ($request->has_postparameter($type) && $request->get_poststring($type))
			{
				if (empty($nb_types))
					$types[1] = addslashes($request->get_poststring($type));
				else
					$types[] = addslashes($request->get_poststring($type));
				$nb_types++;
			}
		}

		foreach ($activities as $key => $activity)
		{
			$new_activity_name = $request->get_value('activity' . $key, '');
			$activities[$key] = (!empty($new_activity_name) && $new_activity_name != $activity) ? $new_activity_name : $activity;
		}

		$nb_activities = count($activities);
		for ($i = 1; $i <= $this->max_input; $i++)
		{
			$activity = 'activity_' . $i;
			if ($request->has_postparameter($activity) && $request->get_poststring($activity))
			{
				if (empty($nb_activities))
					$activities[1] = addslashes($request->get_poststring($activity));
				else
					$activities[] = addslashes($request->get_poststring($activity));
				$nb_activities++;
			}
		}

		$this->config->set_types($types);
		$this->config->set_activities($activities);
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		$this->config->set_categories_number_per_page($this->form->get_value('categories_number_per_page'));
		$this->config->set_columns_number_per_line($this->form->get_value('columns_number_per_line'));
		$this->config->set_category_display_type($this->form->get_value('category_display_type')->get_raw_value());

		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		SponsorsConfig::save();
		SponsorsService::get_categories_manager()->regenerate_cache();
	}
}
?>
