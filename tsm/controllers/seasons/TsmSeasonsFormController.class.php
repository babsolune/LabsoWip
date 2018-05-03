<?php
/*##################################################
 *                   TsmSeasonsFormController.class.php
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

class TsmSeasonsFormController extends ModuleController
{
    private $lang,
            $tsm_lang,
            $form,
            $submit_button,
            $season,
            $view,
            $config,
            $is_new_season;

    public function execute(HTTPRequestCustom $request)
    {
        $this->init();
        $this->check_season_auth();
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
		$this->lang = LangLoader::get('season', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('seasons', $this->get_season()->get_id() === null ? $this->lang['season.add'] : $this->lang['season.edit']);
		$form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldCheckbox('is_calendar', $this->lang['season.type.calendar'], $this->get_season()->is_calendar(),
            array('description' => $this->lang['season.type.desc'])
		));

        $fieldset->add_field($start_date = new FormFieldDate('season_date', $this->lang['season.date'], $this->get_season()->get_season_date(),
			array('required' => true)
		));

        if (TsmseasonsAuthService::check_season_auth($this->get_season()->get_id())->moderation_season())
		{
            $publication_fieldset = new FormFieldsetHTML('publication', $this->lang['season.publication']);
            $form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldCheckbox('is_published', $this->lang['season.is.published'], $this->get_season()->is_published()));
		}

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

    private function check_season_auth()
    {
        $season = $this->get_season();

		if ($season->get_id() === null)
		{
			if (!$season->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$season->is_authorized_to_edit())
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

	private function get_season()
	{
		if ($this->season === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->season = TsmSeasonsService::get_season('WHERE seasons.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_season = true;
				$this->season = new Season();
				$this->season->init_default_properties();
			}
		}
		return $this->season;
	}

    private function save()
    {
		$season = $this->get_season();

        if($this->form->get_value('is_calendar'))
            $season->calendar_season_type();
        else
            $season->date_season_type();

        $season->set_season_date($this->form->get_value('season_date'));

        if(TsmSeasonsAuthService::check_season_auth($season->get_id())->moderation_season())
        {
            if($this->form->get_value('is_published'))
                $season->published();
            else
                $season->not_published();
        }
        else
            $season->not_published();


		if ($season->get_id() === null)
		{
			$id = TsmSeasonsService::add_season($season);
            $season->set_id($id);
		}
		else
		{
			$id = $season->get_id();
			TsmSeasonsService::update_season($season);
		}
    }

	private function redirect()
	{
		$season = $this->get_season();

		if ($season->is_published())
		{
			if ($this->is_new_season)
				AppContext::get_response()->redirect(TsmUrlBuilder::display_season($season->get_id(), $season->get_name()), StringVars::replace_vars($this->lang['season.message.success.add'], array('name' => $season->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : TsmUrlBuilder::display_season($season->get_id(), $season->get_name())), StringVars::replace_vars($this->lang['season.message.success.edit'], array('name' => $season->get_name())));
		}
        else
            AppContext::get_response()->redirect(TsmUrlBuilder::home());
	}

	private function generate_response(View $view)
	{
        $season = $this->get_season();

        $response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());

        if($season->get_id() === null)
        {
            $graphical_environment->set_page_title($this->lang['season.add']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['season.add'], $this->lang['seasons.season']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::add_season());
			$breadcrumb->add($this->lang['season.add'], TsmUrlBuilder::add_season());
        }
        else
        {
            $graphical_environment->set_page_title($this->lang['season.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['season.edit'], $this->lang['seasons.season']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::edit_season($season->get_id()));

			$breadcrumb->add($season->get_name(), TsmUrlBuilder::display_season($season->get_id(), $season->get_name()));
			$breadcrumb->add($this->lang['season.edit'], TsmUrlBuilder::edit_season($season->get_id()));
        }
        return $response;
    }
}
?>
