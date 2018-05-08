<?php
/*##################################################
 *                   TsmCompetitionsFormController.class.php
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

class TsmCompetitionsFormController extends AdminModuleController
{
	private $lang,
            $tsm_lang,
            $type_lang,
            $form,
            $submit_button,
            $competition,
            $view,
            $is_new_competition;

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

		$view->put('FORM', $this->form->display());

		return $this->generate_response($view);
    }

	private function init()
	{
		$this->lang = LangLoader::get('competition', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
		$this->type_lang = LangLoader::get('types', 'tsm');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('competition', $this->get_competition()->get_id() === null ? $this->lang['competition.add'] : $this->lang['competition.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('season', $this->lang['competition.season'], $this->get_competition()->get_season(), $this->list_seasons(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('division', $this->lang['competition.division'], $this->get_competition()->get_division(), $this->list_divisions(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('compet_type', $this->lang['competition.compet.type'], $this->get_competition()->get_compet_type(), $this->list_compet_type(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldRadioChoice('match_type', $this->type_lang['match.type.1'], $this->get_competition()->get_match_type(),
			array(
				new FormFieldRadioChoiceOption($this->type_lang['match.type.1'], 1),
				new FormFieldRadioChoiceOption($this->type_lang['match.type.2'], 2),
			),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('enslavement', $this->lang['competition.sub.compet'], $this->get_competition()->is_sub_compet(),
			array('events' => array('click' => '
			if (HTMLForms.getField("enslavement").getValue()) {
				HTMLForms.getField("compet_master").enable();
				HTMLForms.getField("sub_rank").enable();
			} else {
				HTMLForms.getField("compet_master").disable();
				HTMLForms.getField("sub_rank").disable();
			}'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('compet_master', $this->lang['competition.compet.master'], $this->get_competition()->get_compet_master(), $this->list_competitions(),
			array('hidden' => !$this->get_competition()->is_sub_compet())
		));

		$fieldset->add_field(new FormFieldNumberEditor('sub_rank', $this->lang['competition.sub.rank'], $this->get_competition()->get_sub_rank(),
			array('description' => $this->lang['competition.sub.rank.desc'], 'hidden' => !$this->get_competition()->is_sub_compet())
		));

		if (TsmcompetitionsAuthService::check_competition_auth($this->get_competition()->get_id())->moderation_competition())
		{
            $publication_fieldset = new FormFieldsetHTML('publication', $this->lang['competition.publication']);
            $form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldCheckbox('is_published', $this->lang['competition.is.published'], $this->get_competition()->is_published()));
		}

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

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
		$competition = $this->get_competition();

        $competition->set_season(new Season($this->form->get_value('season')->get_raw_value()));
        $competition->set_division(new Division($this->form->get_value('division')->get_raw_value()));
        $competition->set_compet_type($this->form->get_value('compet_type')->get_raw_value());
        $competition->set_match_type($this->form->get_value('match_type')->get_raw_value());

        if($this->form->get_value('is_sub_compet')) {
            $competition->subservient();
			$competition->set_compet_master($this->form->get_value('compet_master')->get_raw_value());
			$competition->set_sub_rank($this->form->get_value('sub_rank'));
		} else
            $competition->not_subservient();

        if(TsmCompetitionsAuthService::check_competition_auth($competition->get_id())->moderation_competition())
        {
            if($this->form->get_value('is_published'))
                $competition->published();
            else
                $competition->not_published();
        }
        else
            $competition->not_published();


		if ($competition->get_id() === null)
		{
			$id = TsmCompetitionsService::add_competition($competition);
            $competition->set_id($id);
		}
		else
		{
			$id = $competition->get_id();
			TsmCompetitionsService::update_competition($competition);
		}
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

	private function list_seasons()
	{
		$options = array();

		if ($this->get_competition()->get_id() === null)
			$options[] = new FormFieldSelectChoiceOption('', '');

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . TsmSetup::$tsm_season . ' tsm_season
		ORDER BY season_date DESC'
		);

		while($row = $result->fetch())
		{
			$season = new Season();
			$season->set_properties($row);

			$options[] = new FormFieldSelectChoiceOption($season->get_name(), $season->get_id());
		}

		return $options;
	}

	private function list_divisions()
	{
		$options = array();

		if ($this->get_competition()->get_id() === null)
			$options[] = new FormFieldSelectChoiceOption('', '');

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . TsmSetup::$tsm_division . ' tsm_division
		ORDER BY id ASC'
		);

		while($row = $result->fetch())
		{
			$division = new Division();
			$division->set_properties($row);

			$options[] = new FormFieldSelectChoiceOption($division->get_name(), $division->get_id());
		}

		return $options;
	}

	private function list_compet_type()
	{
		$options = array();

		if ($this->get_competition()->get_id() === null)
			$options[] = new FormFieldSelectChoiceOption('', '');

		for ($i = 1; $i <= 5 ; $i++)
		{
			$options[] = new FormFieldSelectChoiceOption($this->type_lang['compet.type.' . $i], $i);
		}

		return $options;
	}

	private function list_competitions()
	{
		$options = array();

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . TsmSetup::$tsm_competition . ' tsm_competition
		ORDER BY id ASC'
		);

		while($row = $result->fetch())
		{
			$competition = new Competition();
			$competition->set_properties($row);

			$options[] = new FormFieldSelectChoiceOption($competition->get_division()->get_name(), $competition->get_id());
		}

		return $options;
	}

	private function generate_response(View $view)
	{
        $competition = $this->get_competition();
        $season = $this->get_competition()->get_season();
        $division = $this->get_competition()->get_division();

        $response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());

        if($competition->get_id() === null)
        {
            $graphical_environment->set_page_title($this->lang['competition.add']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['competition.add'], $this->lang['competitions.competition']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::add_competition());
			$breadcrumb->add($this->lang['competition.add'], TsmUrlBuilder::add_competition());
        }
        else
        {
            $graphical_environment->set_page_title($this->lang['competition.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['competition.edit'], $this->lang['competitions.competition']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::edit_competition($season()->get_id(),$season()->get_name(),$competition->get_id(), $division->get_rewrited_name()));

			$breadcrumb->add($competition->get_division()->get_name());
			$breadcrumb->add($this->lang['competition.edit'], TsmUrlBuilder::edit_competition($season()->get_id(),$season()->get_name(),$competition->get_id(), $division->get_rewrited_name()));
        }
        return $response;
    }
}
?>
