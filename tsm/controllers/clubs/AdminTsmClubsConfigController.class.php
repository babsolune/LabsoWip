<?php
/*##################################################
 *                   AdminTsmClubsConfigController.class.php
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

class AdminTsmClubsConfigController extends AdminModuleController
{
	private $form,
			$submit_button,
			$lang,
			$config;

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

		return new AdminTsmDisplayResponse($tpl, $this->lang['clubs.config']);
	}

	private function init()
	{
		$this->config = TsmConfig::load();
		$this->lang = LangLoader::get('club', 'tsm');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('club_config', $this->lang['clubs.config']);
		$form->add_fieldset($fieldset);

        if ($this->config->is_gmap_active())
        {
            $fieldset->add_field(new GoogleMapsFormFieldMapAddress('default_address', $this->lang['default.address'], new GoogleMapsMarker($this->config->get_default_address(), $this->config->get_default_latitude(), $this->config->get_default_longitude()),
    			array('description' => $this->lang['default.address.desc'], 'always_display_marker' => true)
    		));
        } else {
            $fieldset->add_field(new FormFieldFree('default_address', $this->lang['default.address'], $this->lang['no.gmap']),
    			array('description' => $this->lang['default.address.desc']
            ));
        }

		$fieldset->add_field(new FormFieldCheckbox('new_window', $this->lang['new.window'], $this->config->get_new_window(), array(
            'description' => $this->lang['new.window.desc']
        )));

        $fieldset->add_field(new FormFieldNumberEditor('cols_nb', $this->lang['display.clubs.mosaic.cols.nb'], $this->config->get_clubs_cols_nb(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_clubs', $this->lang['display.clubs'], $this->config->get_clubs_display(),
			array(
				new FormFieldSelectChoiceOption($this->lang['display.clubs.mosaic'], TsmConfig::MOSAIC_DISPLAY),
				new FormFieldSelectChoiceOption($this->lang['display.clubs.list'], TsmConfig::LIST_DISPLAY),
				new FormFieldSelectChoiceOption($this->lang['display.clubs.table'], TsmConfig::TABLE_DISPLAY)
			)
		));

		$fieldset_authorizations = new FormFieldsetHTML('club_auth_fieldset', $this->lang['club.auth']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['club.auth.read'], TsmClubsAuthService::READ_CLUB_AUTH),
			new ActionAuthorization($this->lang['club.auth.write'], TsmClubsAuthService::WRITE_CLUB_AUTH),
			new ActionAuthorization($this->lang['club.auth.contrib'], TsmClubsAuthService::CONTRIBUTION_CLUB_AUTH),
			new ActionAuthorization($this->lang['club.auth.modo'], TsmClubsAuthService::MODERATION_CLUB_AUTH)
		));
		$auth_setter = new FormFieldAuthorizationsSetter('club_auth', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_club_auth());
		$fieldset_authorizations->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
        if ($this->config->is_gmap_active())
        {
			$default_marker = new GoogleMapsMarker();
			$default_marker->set_properties(TextHelper::unserialize($this->form->get_value('default_address')));
			$this->config->set_default_address($default_marker->get_address());
    		$this->config->set_default_latitude($default_marker->get_latitude());
    		$this->config->set_default_longitude($default_marker->get_longitude());
        }

		$this->config->set_new_window($this->form->get_value('new_window'));
		$this->config->set_clubs_cols_nb($this->form->get_value('cols_nb'));
		$this->config->set_clubs_display($this->form->get_value('display_clubs')->get_raw_value());

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		TsmConfig::save();
	}
}
?>
