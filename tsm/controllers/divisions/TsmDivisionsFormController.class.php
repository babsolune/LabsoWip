<?php
/*##################################################
 *                   TsmDivisionsFormController.class.php
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

class TsmDivisionsFormController extends ModuleController
{
    private $lang,
            $tsm_lang,
            $form,
            $submit_button,
            $division,
            $view,
            $is_new_division;

    public function execute(HTTPRequestCustom $request)
    {
        $this->init();
        $this->check_division_auth();
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
		$this->lang = LangLoader::get('division', 'tsm');
		$this->tsm_lang = LangLoader::get('common', 'tsm');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('divisions', $this->get_division()->get_id() === null ? $this->lang['division.add'] : $this->lang['division.edit']);
		$form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldTextEditor('name', $this->lang['division.name'], $this->get_division()->get_name()));

        if (TsmdivisionsAuthService::check_division_auth($this->get_division()->get_id())->moderation_division())
		{
            $publication_fieldset = new FormFieldsetHTML('publication', $this->tsm_lang['form.publication']);
            $form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldCheckbox('is_published', $this->tsm_lang['form.is.published'], $this->get_division()->is_published()));
		}

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

    private function check_division_auth()
    {
        $division = $this->get_division();

		if ($division->get_id() === null)
		{
			if (!$division->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$division->is_authorized_to_edit())
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

	private function get_division()
	{
		if ($this->division === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->division = TsmDivisionsService::get_division('WHERE divisions.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_division = true;
				$this->division = new Division();
				$this->division->init_default_properties();
			}
		}
		return $this->division;
	}

    private function save()
    {
		$division = $this->get_division();

        $division->set_name($this->form->get_value('name'));
        $division->set_rewrited_name(Url::encode_rewrite($this->form->get_value('name')));

        if(TsmDivisionsAuthService::check_division_auth($division->get_id())->moderation_division())
        {
            if($this->form->get_value('is_published'))
                $division->published();
            else
                $division->not_published();
        }
        else
            $division->not_published();


		if ($division->get_id() === null)
		{
			$id = TsmDivisionsService::add_division($division);
            $division->set_id($id);
		}
		else
		{
			$id = $division->get_id();
			TsmDivisionsService::update_division($division);
		}
    }

	private function redirect()
	{
		$division = $this->get_division();

		if ($division->is_published())
		{
            if($this->is_new_division)
			    AppContext::get_response()->redirect(TsmUrlBuilder::divisions_manager(), StringVars::replace_vars($this->lang['division.message.success.add'], array('name' => $division->get_name())));
            else
                AppContext::get_response()->redirect(TsmUrlBuilder::divisions_manager(), StringVars::replace_vars($this->lang['division.message.success.edit'], array('name' => $division->get_name())));
		}
        else {
            if($this->is_new_division)
                AppContext::get_response()->redirect(TsmUrlBuilder::divisions_manager(), StringVars::replace_vars($this->lang['division.message.success.add.not.published'], array('name' => $division->get_name())));
            else
                AppContext::get_response()->redirect(TsmUrlBuilder::divisions_manager(), StringVars::replace_vars($this->lang['division.message.success.add.not.published'], array('name' => $division->get_name())));
        }

	}

	private function generate_response(View $view)
	{
        $division = $this->get_division();

        $response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->tsm_lang['tsm.module.title'], TsmUrlBuilder::home());

        if($division->get_id() === null)
        {
            $graphical_environment->set_page_title($this->lang['division.add']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['division.add'], $this->lang['divisions.division']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::add_division());
			$breadcrumb->add($this->lang['division.add'], TsmUrlBuilder::add_division());
        }
        else
        {
            $graphical_environment->set_page_title($this->lang['division.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['division.edit'], $this->lang['divisions.division']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(TsmUrlBuilder::edit_division($division->get_id()));

			$breadcrumb->add($division->get_name());
			$breadcrumb->add($this->lang['division.edit'], TsmUrlBuilder::edit_division($division->get_id()));
        }
        return $response;
    }
}
?>
