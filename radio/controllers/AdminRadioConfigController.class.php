<?php
/*##################################################
 *		                         AdminRadioConfigController.class.php
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

class AdminRadioConfigController extends AdminModuleController
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
	 * @var RadioConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminRadioDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'radio');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = RadioConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('mini_configuration', $this->lang['radio.mini.config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('radio_name', $this->lang['radio.name'], $this->config->get_radio_name()));

		$fieldset->add_field(new FormFieldUploadFile('radio_url', $this->lang['radio.url'], $this->config->get_radio_url()->relative()));

		$fieldset->add_field(new FormFieldUploadPictureFile('radio_img', $this->lang['radio.img'], $this->config->get_radio_img()->relative()));

		$fieldset->add_field(new FormFieldCheckbox('radio_popup', $this->lang['radio.popup'], $this->config->is_radio_popup()));

		$fieldset->add_field(new FormFieldCheckbox('radio_autoplay', $this->lang['radio.autoplay'], $this->config->is_radio_autoplay()));

		$module_fieldset = new FormFieldsetHTML('module_configuration', $this->lang['radio.module.config']);
		$form->add_fieldset($module_fieldset);

		$module_fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display_type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['radio.display.block'], RadioConfig::DISPLAY_BLOCK),
				new FormFieldSelectChoiceOption($this->lang['radio.display.table'], RadioConfig::DISPLAY_TABLE),
				new FormFieldSelectChoiceOption($this->lang['radio.display.calendar'], RadioConfig::DISPLAY_CALENDAR),
			)
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

		// Documentation
	   	$doc_fieldset = new FormFieldsetHTML('documentation', $this->lang['radio.documentation']);
	   	$form->add_fieldset($doc_fieldset);
	   	$doc_fieldset->add_field(new FormFieldRichTextEditor('documentation_content', '', $this->config->get_documentation(), array('rows' => 25)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_radio_name($this->form->get_value('radio_name'));
		$this->config->set_radio_url($this->form->get_value('radio_url'));
		$this->config->set_radio_img($this->form->get_value('radio_img'));
		$this->config->set_radio_popup($this->form->get_value('radio_popup'));
		$this->config->set_radio_autoplay($this->form->get_value('radio_autoplay'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_documentation($this->form->get_value('documentation_content'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		RadioConfig::save();
		RadioService::get_categories_manager()->regenerate_cache();
	}
}
?>
