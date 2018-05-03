<?php
/*##################################################
 *                   TsmClubsFormController.class.php
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

class TsmClubsFormController extends ModuleController
{
    private $lang,
            $tsm_lang,
            $form,
            $submit_button,
            $club,
            $view,
            $config,
            $is_new_club;

    public function execute(HTTPRequestCustom $request)
    {
        $this->init();
        $this->check_club_auth();
        $this->build_form($request);
		$view = new StringTemplate('# INCLUDE FORM #');
		$view->add_lang($this->lang);
		$view->add_lang($this->tsm_lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$view->put('FORM', $this->form->display());

		return $this->generate_response($view);
    }

	private function init()
	{
        $this->config = TsmConfig::load();
		$this->lang = LangLoader::get('club', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('clubs', $this->get_club()->get_id() === null ? $this->lang['club.add'] : $this->lang['club.edit']);
		$form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldTextEditor('name', $this->lang['club.name'], $this->get_club()->get_name(), array('required' => true)));

        $fieldset->add_field(new FormFieldUrlEditor('website_url', $this->lang['club.website.url'], $this->get_club()->get_website()->absolute()));

        $fieldset->add_field(new FormFieldUploadPictureFile('logo', $this->lang['club.logo'], $this->get_club()->get_logo()->relative()));

        $fieldset->add_field(new FormFieldUploadPictureFile('logo_mini', $this->lang['club.logo.mini'], $this->get_club()->get_logo_mini()->relative(), array(
            'description' => $this->lang['club.logo.mini.desc']
        )));

        $fieldset->add_field(new ClubsFormFieldColors('colors', $this->lang['club.colors'], $this->get_club()->get_colors()));

        $fieldset->add_field(new ClubsFormFieldContact('contact', $this->lang['club.contact'], $this->get_club()->get_contact(), array('description' => $this->lang['club.contact.desc'])));

		$unserialized_value = @unserialize($this->get_club()->get_location());
		$location_value = $unserialized_value !== false ? $unserialized_value : $this->get_club()->get_location();

        $location = ''; $street_number = ''; $route = ''; $postal_code = ''; $city = ''; $department = ''; $state = ''; $country = '';
		if (is_array($location_value) && (isset($location_value['address']) || isset($location_value['city'])))
        {
            if (isset($location_value['address']))
            {
                $location = $location_value['address'];
            } else if (isset($location_value['city']))
            {
                $location = $location_value['street_number'] . $location_value['city'];
                $street_number = $location_value['street_number'];
                $route = $location_value['route'];
                $postal_code = $location_value['postal_code'];
                $city = $location_value['city'];
                $department = $location_value['department'];
                $state = $location_value['state'];
                $country = $location_value['country'];
            }
        }

        if($this->config->is_gmap_active())
        {
            $fieldset->add_field(new ClubsFormFieldLocation('location', $this->lang['club.headquarter.address'], $this->club->get_location(),
				array('description' => $this->lang['club.headquarter.address.desc']
            )));

            $fieldset->add_field(new GoogleMapsFormFieldMapAddress('gps', $this->lang['club.stadium.location'], new GoogleMapsMarker($this->get_club()->get_stadium_address(), $this->get_club()->get_stadium_latitude(), $this->get_club()->get_stadium_longitude()),
    			array('description' => $this->lang['club.stadium.location.desc'], 'always_display_marker' => true)
    		));
        } else {
            $fieldset->add_field(new FormFieldShortMultilineTextEditor('location', $this->lang['club.headquarter.address'], $this->club->get_location()));
        }

        $social_fieldset = new FormFieldsetHTML('social_network', $this->lang['club.social.network']);
        $form->add_fieldset($social_fieldset);

        $social_fieldset->add_field(new FormFieldUrlEditor('facebook_link', $this->lang['club.label.facebook'], $this->get_club()->get_facebook_link()->absolute(), array(
            'placeholder' => $this->lang['club.placeholder.facebook']
        )));

        $social_fieldset->add_field(new FormFieldUrlEditor('twitter_link', $this->lang['club.label.twitter'], $this->get_club()->get_twitter_link()->absolute(), array(
            'placeholder' => $this->lang['club.placeholder.twitter']
        )));

        $social_fieldset->add_field(new FormFieldUrlEditor('gplus_link', $this->lang['club.label.gplus'], $this->get_club()->get_gplus_link()->absolute(), array(
            'placeholder' => $this->lang['club.placeholder.gplus']
        )));

        if (TsmClubsAuthService::check_club_auth($this->get_club()->get_id())->moderation_club())
		{
            $publication_fieldset = new FormFieldsetHTML('publication', $this->lang['club.publication']);
            $form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldCheckbox('is_published', $this->lang['club.is.published'], $this->get_club()->is_published()));
		}

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

    private function check_club_auth()
    {
        $club = $this->get_club();

		if ($club->get_id() === null)
		{
			if (!$club->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$club->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
    }

	private function get_club()
	{
		if ($this->club === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->club = TsmClubsService::get_club('WHERE clubs.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_club = true;
				$this->club = new Club();
				$this->club->init_default_properties();
			}
		}
		return $this->club;
	}

    private function save()
    {
		$club = $this->get_club();

		$club->set_name($this->form->get_value('name'));
		$club->set_rewrited_name(Url::encode_rewrite($club->get_name()));

		$club->set_website(new Url($this->form->get_value('website_url')));
		$club->set_logo(new Url($this->form->get_value('logo')));
		$club->set_logo_mini(new Url($this->form->get_value('logo_mini')));
        $club->set_colors($this->form->get_value('colors'));
        $club->set_contact($this->form->get_value('contact'));
		$club->set_facebook_link(new Url($this->form->get_value('facebook_link')));
		$club->set_twitter_link(new Url($this->form->get_value('twitter_link')));
		$club->set_gplus_link(new Url($this->form->get_value('gplus_link')));

        $club->set_location($this->form->get_value('location'));
        if($this->config->is_gmap_active()) {
            $stadium = new GoogleMapsMarker();
			$stadium->set_properties(TextHelper::unserialize($this->form->get_value('gps')));
			$club->set_stadium_address($stadium->get_address());
			$club->set_stadium_latitude($stadium->get_latitude());
			$club->set_stadium_longitude($stadium->get_longitude());
        }

        if(TsmClubsAuthService::check_club_auth($club->get_id())->moderation_club())
        {
            if($this->form->get_value('is_published'))
                $club->published();
            else
                $club->not_published();
        }
        else
            $club->not_published();


		if ($club->get_id() === null)
		{
			$id = TsmClubsService::add_club($club);
            $club->set_id($id);
		}
		else
		{
			$id = $club->get_id();
			TsmClubsService::update_club($club);
		}
    }

	private function redirect()
	{
		$club = $this->get_club();

		if ($club->is_published())
		{
			if ($this->is_new_club)
				AppContext::get_response()->redirect(TsmUrlBuilder::display_club($club->get_id(), $club->get_rewrited_name()), StringVars::replace_vars($this->lang['club.message.success.add'], array('name' => $club->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : TsmUrlBuilder::display_club($club->get_id(), $club->get_rewrited_name())), StringVars::replace_vars($this->lang['club.message.success.edit'], array('name' => $club->get_name())));
		}
        else
            AppContext::get_response()->redirect(TsmUrlBuilder::home_club());
	}

	private function generate_response(View $view)
	{
        $club = $this->get_club();

        $response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());

        if($club->get_id() === null)
        {
            $graphical_environment->set_page_title($this->lang['club.add']);
    		$breadcrumb->add($this->lang['clubs.clubs'], TsmUrlBuilder::home_club());
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['club.add'], $this->lang['clubs.club']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::add_club());
			$breadcrumb->add($this->lang['club.add'], TsmUrlBuilder::add_club());
        }
        else
        {
            $graphical_environment->set_page_title($this->lang['club.edit']);
    		$breadcrumb->add($this->lang['clubs.clubs'], TsmUrlBuilder::home_club());
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['club.edit'], $this->lang['clubs.club']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::edit_club($club->get_id()));

			$breadcrumb->add($club->get_name(), TsmUrlBuilder::display_club($club->get_id(), $club->get_rewrited_name()));
			$breadcrumb->add($this->lang['club.edit'], TsmUrlBuilder::edit_club($club->get_id()));
        }
        return $response;
    }
}
?>
