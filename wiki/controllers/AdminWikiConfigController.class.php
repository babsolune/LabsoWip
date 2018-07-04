<?php
/*##################################################
 *                   AdminWikiConfigController.class.php
 *                            -------------------
 *   begin                : May 25, 2018
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

class AdminWikiConfigController extends AdminModuleController
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
	 * @var WikiConfig
	 */
	private $config;
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('number_character_to_cut')->set_hidden($this->config->get_display_type() === WikiConfig::DISPLAY_TABLE);
			$this->form->get_field_by_id('number_cols_display_per_line')->set_hidden($this->config->get_display_type() !== WikiConfig::DISPLAY_MOSAIC);
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminWikiDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = WikiConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('wiki_configuration', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('display_icon_cats', $this->lang['wiki_configuration.display_icon_cats'], $this->config->are_cats_icon_enabled()));

		$fieldset->add_field(new FormFieldCheckbox('display_color_cats', $this->lang['wiki_configuration.display_color_cats'], $this->config->are_cats_color_enabled()));

		$fieldset->add_field(new FormFieldCheckbox('display_descriptions_to_guests', $this->lang['wiki_configuration.display_descriptions_to_guests'], $this->config->are_descriptions_displayed_to_guests()));

		$fieldset->add_field(new FormFieldFree('1_separator', '', ''));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['wiki_configuration.display_type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['wiki_configuration.display_type.mosaic'], WikiConfig::DISPLAY_MOSAIC),
				new FormFieldSelectChoiceOption($this->lang['wiki_configuration.display_type.list'], WikiConfig::DISPLAY_LIST),
				new FormFieldSelectChoiceOption($this->lang['wiki_configuration.display_type.table'], WikiConfig::DISPLAY_TABLE)
			),
			array('events' => array('change' => '
				if (HTMLForms.getField("display_type").getValue() === \'' . WikiConfig::DISPLAY_MOSAIC . '\') {
					HTMLForms.getField("number_character_to_cut").enable();
					HTMLForms.getField("number_cols_display_per_line").enable();
				} else if (HTMLForms.getField("display_type").getValue() === \'' . WikiConfig::DISPLAY_LIST . '\') {
					HTMLForms.getField("number_character_to_cut").enable();
					HTMLForms.getField("number_cols_display_per_line").disable();
				} else {
					HTMLForms.getField("number_character_to_cut").disable();
					HTMLForms.getField("number_cols_display_per_line").disable();
				}'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_character_to_cut', $this->lang['wiki_configuration.number_character_to_cut'], $this->config->get_number_character_to_cut(),
			array('min' => 20, 'max' => 1000, 'required' => true,'hidden' => $this->config->get_display_type() === WikiConfig::DISPLAY_TABLE),
			array(new FormFieldConstraintIntegerRange(20, 1000))
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_cols_display_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_number_cols_display_per_line(),
			array(
				'min' => 1,
				'max' => 6,
				'required' => true,
				'description' => $this->admin_common_lang['config.columns_number_per_line.description'],
				'hidden' => $this->config->get_display_type() !== WikiConfig::DISPLAY_MOSAIC
			),
			array(new FormFieldConstraintIntegerRange(1, 6))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations'],
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
		$this->config->set_number_cols_display_per_line($this->form->get_value('number_cols_display_per_line'));

		if ($this->form->get_value('display_icon_cats'))
			$this->config->enable_cats_icon();
		else
			$this->config->disable_cats_icon();

		if ($this->form->get_value('display_color_cats'))
			$this->config->enable_cats_color();
		else
			$this->config->disable_cats_color();

		$this->config->set_number_character_to_cut($this->form->get_value('number_character_to_cut', $this->config->get_number_character_to_cut()));

		if ($this->form->get_value('display_descriptions_to_guests'))
			$this->config->display_descriptions_to_guests();
		else
			$this->config->hide_descriptions_to_guests();

		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		WikiConfig::save();
		WikiService::get_categories_manager()->regenerate_cache();
	}
}
?>
