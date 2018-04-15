<?php
/*##################################################
 *                               ClubsFormController.class.php
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

class ClubsFormController extends ModuleController
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
	private $common_lang;
    private $config;
    private $tpl;

	private $club;
	private $is_new_club;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$this->tpl->put_all(array(
            'FORM' => $this->form->display(),
            'GMAP_API' => $this->config->is_gmap_api()
        ));

		return $this->build_response($this->tpl);
	}

	private function init()
	{
        $this->tpl = new FileTemplate('clubs/ClubsFormController.tpl');
		$this->lang = LangLoader::get('common', 'clubs');
        $this->tpl->add_lang($this->lang);
		$this->sport_type_lang = LangLoader::get('sport-types', 'clubs');
		$this->rugby_lang = LangLoader::get('rugby-district', 'clubs');
		$this->football_lang = LangLoader::get('football-district', 'clubs');
		$this->common_lang = LangLoader::get('common');
        $this->config = ClubsConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('clubs', $this->get_club()->get_id() === null ? $this->lang['clubs.add'] : $this->lang['clubs.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->common_lang['form.name'], $this->get_club()->get_name(), array('required' => true)));

		if (ClubsService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(ClubsService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['form.category'], $this->get_club()->get_id_category(), $search_category_children_options));
		}

        if($this->config->get_sport_type() === 1) {
            $fieldset->add_field(new FormFieldSimpleSelectChoice('district', $this->rugby_lang['rugby.district'], $this->get_club()->get_district(), $this->list_districts()));
        } elseif($this->config->get_sport_type() === 2) {
            $fieldset->add_field(new FormFieldSimpleSelectChoice('district', $this->football_lang['football.district'], $this->get_club()->get_district(), $this->list_districts()));
        } else {
            $fieldset->add_field(new FormFieldFree('district', $this->lang['district'], $this->sport_type_lang['clubs.sport.type.none']));
        }

        $fieldset->add_field(new FormFieldUploadPictureFile('logo', $this->lang['clubs.logo'], $this->get_club()->get_logo()->relative()));

        $fieldset->add_field(new FormFieldUploadPictureFile('logo_mini', $this->lang['clubs.logo.mini'], $this->get_club()->get_logo_mini()->relative(), array(
            'description' => $this->lang['clubs.logo.mini.desc']
        )));

        $fieldset->add_field(new ClubsFormFieldColors('colors', $this->lang['clubs.colors'], $this->get_club()->get_colors(), array(
            'description' => $this->lang['clubs.colors.desc']
        )));

		$fieldset->add_field(new FormFieldUrlEditor('website_url', $this->lang['clubs.website.url'], $this->get_club()->get_website_url()->absolute()));

        $fieldset->add_field(new FormFieldTextEditor('club_phone', $this->lang['clubs.labels.phone'], $this->get_club()->get_club_phone()));

        $fieldset->add_field(new FormFieldMailEditor('club_email', $this->lang['clubs.labels.email'], $this->get_club()->get_club_email()));

		$unserialized_value = @unserialize($this->club->get_location());
		$location_value = $unserialized_value !== false ? $unserialized_value : $this->club->get_location();

		$location = ''; $street_number = ''; $route = ''; $postal_code = ''; $city = ''; $department = ''; $state = ''; $country = '';
		if (is_array($location_value) && (isset($location_value['address']) || isset($location_value['city'])))
        {
            if (isset($location_value['address']))
            {
                $location = $location_value['address'];
            } elseif (isset($location_value['city']))
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
		// else if (!is_array($location_value))
		// 	$location = $location_value;

        if($this->config->is_gmap_api()) {
            $fieldset->add_field(new ClubsFormFieldLocation('location', $this->lang['clubs.headquarter.address'], $this->club->get_location(), array(
                'description' => $this->lang['clubs.headquarter.address.desc']
            )));
            // $fieldset->add_field(new LeafletFormFieldCompleteAddress('location', $this->lang['clubs.headquarter.address'], $location, array(
            //     'description' => $this->lang['clubs.headquarter.address.desc']
            // )));

            $fieldset->add_field(new GoogleMapsFormFieldMapAddress('gps', $this->lang['clubs.stadium.location'], new GoogleMapsMarker($this->get_club()->get_stadium_address(), $this->get_club()->get_stadium_latitude(), $this->get_club()->get_stadium_longitude()),
    			array('description' => $this->lang['clubs.stadium.location.desc'], 'always_display_marker' => true)
    		));
        } else {
            $fieldset->add_field(new FormFieldFree('location', $this->lang['clubs.headquarter.address'], $this->lang['clubs.no.gmap']));
            $fieldset->add_field(new FormFieldFree('stadium_location', $this->lang['clubs.stadium.location'], $this->lang['clubs.no.gmap']));
        }

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.description'], $this->get_club()->get_contents(), array('rows' => 15)));

        $social_fieldset = new FormFieldsetHTML('social_network', $this->lang['clubs.social.network']);
        $form->add_fieldset($social_fieldset);

        $social_fieldset->add_field(new FormFieldUrlEditor('facebook_link', $this->lang['clubs.labels.facebook'], $this->get_club()->get_facebook_link()->absolute(), array(
            'placeholder' => $this->lang['clubs.placeholder.facebook']
        )));

        $social_fieldset->add_field(new FormFieldUrlEditor('twitter_link', $this->lang['clubs.labels.twitter'], $this->get_club()->get_twitter_link()->absolute(), array(
            'placeholder' => $this->lang['clubs.placeholder.twitter']
        )));

        $social_fieldset->add_field(new FormFieldUrlEditor('gplus_link', $this->lang['clubs.labels.gplus'], $this->get_club()->get_gplus_link()->absolute(), array(
            'placeholder' => $this->lang['clubs.placeholder.gplus']
        )));

        if (ClubsAuthorizationsService::check_authorizations($this->get_club()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_club()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_club()->is_visible())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_club()->get_status() != Club::NOT_APPROVAL)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('approbation_type', $this->common_lang['form.approbation'], $this->get_club()->get_approbation_type(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Club::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Club::APPROVAL_NOW),
				)
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function list_districts()
	{
        $this->config = ClubsConfig::load();
		$options = array();

        if($this->config->get_sport_type() === 1) { // Rugby
    		for ($i = 0; $i <= 34 ; $i++)
    		{
    			$options[] =  new FormFieldSelectChoiceOption($this->rugby_lang['rugby.district.' . $i], addslashes($this->rugby_lang['rugby.district.' . $i]));
    		}
        } elseif($this->config->get_sport_type() === 2) { // Football
    		for ($i = 0; $i <= 125 ; $i++)
    		{
    			$options[] =  new FormFieldSelectChoiceOption($this->football_lang['football.district.' . $i], addslashes($this->football_lang['football.district.' . $i]));
    		}
        }
		return $options;
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_club()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['clubs.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!ClubsAuthorizationsService::check_authorizations()->write() && ClubsAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_club()
	{
		if ($this->club === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->club = ClubsService::get_club('WHERE clubs.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_club = true;
				$this->club = new Club();
				$this->club->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->club;
	}

	private function check_authorizations()
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

	private function save()
	{
		$club = $this->get_club();

		$club->set_name($this->form->get_value('name'));
		$club->set_rewrited_name(Url::encode_rewrite($club->get_name()));

		if (ClubsService::get_categories_manager()->get_categories_cache()->has_categories())
			$club->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$club->set_website_url(new Url($this->form->get_value('website_url')));
		$club->set_logo(new Url($this->form->get_value('logo')));
		$club->set_logo_mini(new Url($this->form->get_value('logo_mini')));
        $club->set_colors($this->form->get_value('colors'));
		$club->set_club_phone((string)$this->form->get_value('club_phone'));
		$club->set_club_email((string)$this->form->get_value('club_email'));
		$club->set_facebook_link(new Url($this->form->get_value('facebook_link')));
		$club->set_twitter_link(new Url($this->form->get_value('twitter_link')));
		$club->set_gplus_link(new Url($this->form->get_value('gplus_link')));
		$club->set_contents($this->form->get_value('contents'));

        $club->set_location($this->form->get_value('location'));

        if($this->config->is_gmap_api()) {

            $stadium = new GoogleMapsMarker();
			$stadium->set_properties(TextHelper::unserialize($this->form->get_value('gps')));

			$club->set_stadium_address($stadium->get_address());
			$club->set_stadium_latitude($stadium->get_latitude());
			$club->set_stadium_longitude($stadium->get_longitude());
        }

        if($this->config->get_sport_type() !== 0)
            $club->set_district($this->form->get_value('district')->get_raw_value());

		if (!ClubsAuthorizationsService::check_authorizations($club->get_id_category())->moderation())
		{
			if ($club->get_id() === null )
				$club->set_creation_date(new Date());

			if (ClubsAuthorizationsService::check_authorizations($club->get_id_category())->contribution() && !ClubsAuthorizationsService::check_authorizations($club->get_id_category())->write())
				$club->set_approbation_type(Club::NOT_APPROVAL);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$club->set_creation_date(new Date());
			}
			else
			{
				$club->set_creation_date($this->form->get_value('creation_date'));
			}
			$club->set_approbation_type($this->form->get_value('approbation_type')->get_raw_value());
		}

		if ($club->get_id() === null)
		{
			$id = ClubsService::add($club);
		}
		else
		{
			$id = $club->get_id();
			ClubsService::update($club);
		}

		$this->contribution_actions($club, $id);

		Feed::clear_cache('clubs');
		ClubsCache::invalidate();
		ClubsCategoriesCache::invalidate();
	}

	private function contribution_actions(Club $club, $id)
	{
		if ($club->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($club->get_name());
				$contribution->set_fixing_url(ClubsUrlBuilder::edit($id)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('clubs');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						ClubsService::get_categories_manager()->get_heritated_authorizations($club->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('clubs', $id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$club->set_id($id);
	}

	private function redirect()
	{
		$club = $this->get_club();
		$category = $club->get_category();

		if ($this->is_new_club && $this->is_contributor_member() && !$club->is_visible())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($club->is_visible())
		{
			if ($this->is_new_club)
				AppContext::get_response()->redirect(ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $club->get_id(), $club->get_rewrited_name()), StringVars::replace_vars($this->lang['clubs.message.success.add'], array('name' => $club->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $club->get_id(), $club->get_rewrited_name())), StringVars::replace_vars($this->lang['clubs.message.success.edit'], array('name' => $club->get_name())));
		}
		else
		{
			if ($this->is_new_club)
				AppContext::get_response()->redirect(ClubsUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['clubs.message.success.add'], array('name' => $club->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ClubsUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['clubs.message.success.edit'], array('name' => $club->get_name())));
		}
	}

	private function build_response(View $tpl)
	{
		$club = $this->get_club();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], ClubsUrlBuilder::home());

		if ($club->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['clubs.add']);
			$breadcrumb->add($this->lang['clubs.add'], ClubsUrlBuilder::add($club->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['clubs.add'], $this->lang['module_title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ClubsUrlBuilder::add($club->get_id_category()));
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['clubs.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['clubs.edit'], $this->lang['module_title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ClubsUrlBuilder::edit($club->get_id()));

			$categories = array_reverse(ClubsService::get_categories_manager()->get_parents($club->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), ClubsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $club->get_category();
			$breadcrumb->add($club->get_name(), ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $club->get_id(), $club->get_rewrited_name()));
			$breadcrumb->add($this->lang['clubs.edit'], ClubsUrlBuilder::edit($club->get_id()));
		}

		return $response;
	}
}
?>
