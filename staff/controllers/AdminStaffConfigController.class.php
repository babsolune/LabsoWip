<?php
/**
 *				AdminStaffConfigController.class.php
 *				------------------
 * @copyright 	2005-2019 PHPBoost
 * @license 	https://opensource.org/licenses/GPL-3.0
 *
 * @since 		PHPBoost 5.2 - 2017-06-29
 * @author 		Sebastien LARTIGUE - <babsolune@phpboost.com>
 *
 * @category 	module
 * @package 	staff
 * @subpackage	controllers
 * @desc 		Configuration of the module
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
	private $options_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new AdminStaffDisplayResponse($view, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->config = StaffConfig::load();
		$this->options_config = StaffService::get_options_config();
		$this->lang = LangLoader::get('common', 'staff');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new StaffFormFieldRole('roles', $this->lang['config.role.add'], $this->options_config->get_roles()));

		$fieldset->add_field(new FormFieldCheckbox('avatars', $this->lang['config.display.avatars'], $this->config->are_avatars_shown()));

		$fieldset->add_field(new FormFieldNumberEditor('sub_categories_number_per_line', $this->lang['config.sub.categories.nb'], $this->config->get_sub_categories_nb(),
			array('min' => 1, 'max' => 6, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 6))
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
		StaffService::update_options_config(TextHelper::serialize($this->form->get_value('roles')));

		if($this->form->get_value('avatars'))
			$this->config->show_avatars();
		else
			$this->config->hide_avatars();

		$this->config->set_sub_categories_nb($this->form->get_value('sub_categories_number_per_line'));

		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		StaffConfig::save();
		StaffService::get_categories_manager()->regenerate_cache();
	}
}
?>
