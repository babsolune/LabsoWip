<?php
/*##################################################
 *                               AdminStaffConfigController.class.php
 *                            -------------------
 *   begin                : June 29, 2017
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
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
 */

class AdminStaffConfigController extends AdminModuleController
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
	 * @var StaffConfig
	 */
	private $config;
	private $comments_config;
	private $notation_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('members_number_per_line')->set_hidden($this->config->get_category_display_type() == StaffConfig::DISPLAY_TABLE);
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminStaffDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->config = StaffConfig::load();
		$this->lang = LangLoader::get('common', 'staff');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

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

		$fieldset->add_field(new FormFieldSimpleSelectChoice('category_display_type', $this->lang['config.category_display_type'], $this->config->get_category_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['config.category_display_type.display_blocks'], StaffConfig::DISPLAY_BLOCKS),
				new FormFieldSelectChoiceOption($this->lang['config.category_display_type.display_table'], StaffConfig::DISPLAY_TABLE)
			),
			array('events' => array('click' => '
				if (HTMLForms.getField("category_display_type").getValue() != \'' . StaffConfig::DISPLAY_TABLE . '\') {
					HTMLForms.getField("members_number_per_line").enable();
				} else {
					HTMLForms.getField("members_number_per_line").disable();
				}'
			))
		));

        $fieldset->add_field(new FormFieldNumberEditor('members_number_per_line', $this->lang['config.members_number_per_line'], $this->config->get_members_number_per_line(),
			array('hidden' => $this->config->get_category_display_type() == StaffConfig::DISPLAY_TABLE, 'min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
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

	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		$this->config->set_categories_number_per_page($this->form->get_value('categories_number_per_page'));
		$this->config->set_columns_number_per_line($this->form->get_value('columns_number_per_line'));
		$this->config->set_members_number_per_line($this->form->get_value('members_number_per_line'));
		$this->config->set_category_display_type($this->form->get_value('category_display_type')->get_raw_value());

		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		StaffConfig::save();
		StaffService::get_categories_manager()->regenerate_cache();
	}
}
?>
