<?php
/*##################################################
 *                   TsmCompetitionsFormMatchesController.class.php
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

class TsmCompetitionsFormMatchesController extends AdminModuleController
{
	private $lang,
            $tsm_lang,
            $form,
            $submit_button,
            $competition,
            $view;

	public function execute(HTTPRequestCustom $request)
	{
        $this->init();
        $this->check_competition_auth();
        $this->build_form($request);
		$view = new StringTemplate('# INCLUDE FORM #');
		$view->add_lang($this->lang);
		$view->add_lang($this->tsm_lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$view->put_all(array(
			'FORM' => $this->form->display()
		));

		return $this->generate_response($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('competition', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('matches_management', $this->lang['tools.matches.manager']);
		$form->add_fieldset($fieldset);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

    private function check_competition_auth()
    {
        $competition = $this->get_competition();

		if ($competition->get_id() === null)
		{
			if (!$competition->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$competition->is_authorized_to_edit())
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

	private function get_competition()
	{
		if ($this->competition === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->competition = TsmCompetitionsService::get_competition('WHERE competitions.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_competition = true;
				$this->competition = new Competition();
				$this->competition->init_default_properties();
			}
		}
		return $this->competition;
	}

	private function save()
	{

	}

	private function redirect()
	{
		$competition = $this->get_competition();

		if ($competition->is_published())
		{
			if ($this->is_new_competition)
				AppContext::get_response()->redirect(TsmUrlBuilder::display_competition($competition->get_season()->get_id(), $competition->get_season()->get_name(), $competition->get_id(), $competition->get_division()->get_rewrited_name()), StringVars::replace_vars($this->lang['competition.message.success.add'], array('name' => $competition->get_division()->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : TsmUrlBuilder::display_competition($competition->get_id(), $competition->get_rewrited_name())), StringVars::replace_vars($this->lang['competition.message.success.edit'], array('name' => $competition->get_division()->get_name())));
		}
        else
            AppContext::get_response()->redirect(TsmUrlBuilder::home());
	}

	private function generate_response(View $view)
	{
        $competition = $this->get_competition();
        $season = $competition->get_season();
        $division = $competition->get_division();

        $response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());

        $graphical_environment->set_page_title($this->lang['tools.matches.manager']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['competition.edit'], $this->lang['competitions.competition']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::edit_competition_matches($competition->get_id()));

		$breadcrumb->add('plop'); //$division->get_name(), TsmUrlBuilder::edit_competition($season()->get_id(), $season()->get_name(), $competition->get_id(), $division->get_rewrited_name())
		$breadcrumb->add($this->lang['tools.matches.manager']);

        return $response;
    }
}
?>
