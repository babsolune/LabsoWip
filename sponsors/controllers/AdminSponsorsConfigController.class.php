<?php
/*##################################################
 *                   AdminSponsorsConfigController.class.php
 *                            -------------------
 *   begin                : May 20, 2018
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

	private $content_management_config;

	private $lang;
	private $admin_common_lang;

	/**
	 * @var SponsorsConfig
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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminSponsorsDisplayResponse($tpl, $this->lang['config.categories.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = SponsorsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('sponsors_configuration', $this->lang['config.categories.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('new_window', $this->lang['config.new.window'], $this->config->is_new_window()));

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_line', $this->lang['sponsors.items.per.line'], $this->config->get_items_number_per_line(),
			array('min' => 2, 'max' => 6, 'required' => true),
			array(new FormFieldConstraintIntegerRange(2, 6))
		));

		$fieldset->add_field(new LevelsFormField('partnership_levels', $this->lang['sponsors.level.add'], $this->config->get_partnership_levels()));

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
		if($this->form->get_value('new_window'))
			$this->config->open_new_window();
		else
			$this->config->no_new_window();

		$this->config->set_items_number_per_line($this->form->get_value('items_number_per_line'));
		$this->config->set_partnership_levels($this->form->get_value('partnership_levels'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		SponsorsConfig::save();
		SponsorsService::get_categories_manager()->regenerate_cache();
		SponsorsCache::invalidate();
	}
}
?>
