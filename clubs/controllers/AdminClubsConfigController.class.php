<?php
/*##################################################
 *                               AdminClubsConfigController.class.php
 *                            -------------------
 *   begin                : June 23, 2017
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

class AdminClubsConfigController extends AdminModuleController
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
	private $sport_type_lang;

	/**
	 * @var ClubsConfig
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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminClubsDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->config = ClubsConfig::load();
		$this->comments_config = new ClubsComments();
		$this->notation_config = new ClubsNotation();
		$this->lang = LangLoader::get('common', 'clubs');
		$this->sport_type_lang = LangLoader::get('sport-types', 'clubs');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldSimpleSelectChoice('sport_type', $this->sport_type_lang['clubs.sport.type'], $this->config->get_sport_type(), $this->list_sport_types()));

        $fieldset->add_field(new FormFieldCheckbox('new_window', $this->lang['config.new.window'], $this->config->get_new_window(), array(
            'description' => $this->lang['config.new.window.desc']
        )));

        if ($this->config->is_gmap_api())
        {
            $fieldset->add_field(new GoogleMapsFormFieldMapAddress('default_address', $this->lang['config.default.address'], new GoogleMapsMarker($this->config->get_default_address(), $this->config->get_default_latitude(), $this->config->get_default_longitude()),
    			array('description' => $this->lang['config.default.address.desc'], 'always_display_marker' => true)
    		));
        } else {
            $fieldset->add_field(new FormFieldFree('default_adresse', $this->lang['config.default.address'], $this->lang['clubs.no.gmap']),
    			array('description' => $this->lang['config.default.address.desc']
            ));
        }

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
				new FormFieldSelectChoiceOption($this->lang['config.category_display_type.display_all_content'], ClubsConfig::DISPLAY_ALL_CONTENT),
				new FormFieldSelectChoiceOption($this->lang['config.category_display_type.display_table'], ClubsConfig::DISPLAY_TABLE)
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

	private function list_sport_types()
	{
		$options = array();

		for ($i = 0; $i <= 20 ; $i++)
		{
			$options[] =  new FormFieldSelectChoiceOption($this->sport_type_lang['clubs.type.' . $i], $i);
		}

		return $options;
	}

	private function save()
	{
		$this->config->set_new_window($this->form->get_value('new_window'));

		$default_marker = new GoogleMapsMarker();
		$default_marker->set_properties(TextHelper::unserialize($this->form->get_value('default_address')));

        if ($this->config->is_gmap_api())
        {
            $this->config->set_default_address($default_marker->get_address());
    		$this->config->set_default_latitude($default_marker->get_latitude());
    		$this->config->set_default_longitude($default_marker->get_longitude());
        }

		$this->config->set_sport_type($this->form->get_value('sport_type')->get_raw_value());
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		$this->config->set_categories_number_per_page($this->form->get_value('categories_number_per_page'));
		$this->config->set_columns_number_per_line($this->form->get_value('columns_number_per_line'));
		$this->config->set_category_display_type($this->form->get_value('category_display_type')->get_raw_value());

		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		ClubsConfig::save();
		ClubsService::get_categories_manager()->regenerate_cache();
		ClubsCache::invalidate();
	}
}
?>
