<?php
/*##################################################
 *                               StaffItemFormController.class.php
 *                            -------------------
 *   begin                : June 29, 2017
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
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
 */

class StaffItemFormController extends ModuleController
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

	private $member;
	private $is_new_member;

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

		$tpl->put('FORM', $this->form->display());

		return $this->generate_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'staff');
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('staff', $this->get_member()->get_id() === null ? $this->lang['staff.add'] : $this->lang['staff.edit']);
		$form->add_fieldset($fieldset);

		// $fieldset->add_field(new StaffFormFieldSelectUser('user_completition', 'Membre du site', ''));

		$fieldset->add_field(new FormFieldTextEditor('lastname', $this->lang['staff.form.lastname'], $this->get_member()->get_lastname(), array('required' => true)));

        $fieldset->add_field(new FormFieldTextEditor('firstname', $this->lang['staff.form.firstname'], $this->get_member()->get_firstname(), array('required' => true)));

		if (StaffService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(StaffService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_member()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldSimpleSelectChoice('role', $this->lang['staff.form.role'], $this->get_member()->get_role(), $this->role_list()));

        $fieldset->add_field(new FormFieldTelEditor('member_phone', $this->lang['staff.form.member.phone'], $this->get_member()->get_member_phone()));

        $fieldset->add_field(new FormFieldMailEditor('member_email', $this->lang['staff.form.member.email'], $this->get_member()->get_member_email()));

		$fieldset->add_field(new FormFieldUploadPictureFile('picture', $this->lang['staff.form.avatar'], $this->get_member()->get_picture()->relative()));

		$fieldset->add_field(new FormFieldCheckbox('group_leader', $this->lang['staff.form.group.leader'], $this->get_member()->is_group_leader()));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['staff.form.description'], $this->get_member()->get_contents(), array('rows' => 15)));

		if (StaffAuthorizationsService::check_authorizations($this->get_member()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->lang['form.publication']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_member()->get_creation_date(),
				array('required' => true)
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('publication', $this->lang['form.is.published'], $this->get_member()->is_published()));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function role_list()
	{
		$options = array();
		$this->config = StaffConfig::load();
		$roles = $this->config->get_role();

		// laisser un vide en début de liste
		$options[] = new FormFieldSelectChoiceOption('', '');

		$i = 1;
		foreach($roles as $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, Url::encode_rewrite($name));
			$i++;
		}

		return $options;
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_member()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['staff.form.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!StaffAuthorizationsService::check_authorizations()->write() && StaffAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_member()
	{
		if ($this->member === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->member = StaffService::get_member('WHERE staff.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_member = true;
				$this->member = new Member();
				$this->member->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->member;
	}

	private function check_authorizations()
	{
		$member = $this->get_member();

		if ($member->get_id() === null)
		{
			if (!$member->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$member->is_authorized_to_edit())
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
		$member = $this->get_member();

		if ($member->get_order_id() === null)
		{
			$member_nb = StaffService::count('WHERE id_category = :id_category', array('id_category' => $member->get_id_category()));
			$member->set_order_id($member_nb + 1);
		}

		$member->set_lastname($this->form->get_value('lastname'));
		$member->set_firstname($this->form->get_value('firstname'));
		$member->set_rewrited_name(Url::encode_rewrite($member->get_lastname() . '-' . $member->get_firstname()));

		if (StaffService::get_categories_manager()->get_categories_cache()->has_categories())
			$member->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$member->set_contents($this->form->get_value('contents'));
        $member->set_role($this->form->get_value('role')->get_raw_value());
        $member->set_member_phone((string)$this->form->get_value('member_phone'));
        $member->set_member_email((string)$this->form->get_value('member_email'));
		$member->set_picture(new Url($this->form->get_value('picture')));
		$member->set_group_leader($this->form->get_value('group_leader'));

		if (!StaffAuthorizationsService::check_authorizations($member->get_id_category())->moderation())
		{
			if ($member->get_id() === null )
				$member->set_creation_date(new Date());
				
			$member->not_published();
		}
		else
		{
			$member->set_creation_date($this->form->get_value('creation_date'));

			if($this->form->get_value('publication'))
				$member->published();
			else
				$member->not_published();
		}

		if ($member->get_id() === null)
		{
			$id = StaffService::add($member);
		}
		else
		{
			$id = $member->get_id();
			StaffService::update($member);
		}

		$this->contribution_actions($member, $id);

		Feed::clear_cache('staff');
		StaffCategoriesCache::invalidate();
	}

	private function contribution_actions(Member $member, $id)
	{
		if ($member->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled($member->get_lastname() . ' ' .$member->get_firstname());
				$contribution->set_fixing_url(StaffUrlBuilder::edit($id)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('staff');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						StaffService::get_categories_manager()->get_heritated_authorizations($member->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('staff', $id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$member->set_id($id);
	}

	private function redirect()
	{
		$member = $this->get_member();
		$category = $member->get_category();

		if ($this->is_new_member && $this->is_contributor_member() && !$member->is_visible())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($member->is_visible())
		{
			if ($this->is_new_member)
				AppContext::get_response()->redirect(StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $member->get_id(), $member->get_rewrited_name()), StringVars::replace_vars($this->lang['staff.message.success.add'], array('firstname' => $member->get_firstname(), 'lastname' => $member->get_lastname())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $member->get_id(), $member->get_rewrited_name())), StringVars::replace_vars($this->lang['staff.message.success.edit'], array('firstname' => $member->get_firstname(), 'lastname' => $member->get_lastname())));
		}
		else
		{
			if ($this->is_new_member)
				AppContext::get_response()->redirect(StaffUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['staff.message.success.add'], array('firstname' => $member->get_firstname(), 'lastname' => $member->get_lastname())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : StaffUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['staff.message.success.edit'], array('firstname' => $member->get_firstname(), 'lastname' => $member->get_lastname())));
		}
	}

	private function generate_response(View $tpl)
	{
		$member = $this->get_member();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['staff.module.title'], StaffUrlBuilder::home());

		if ($member->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['staff.add']);
			$breadcrumb->add($this->lang['staff.add'], StaffUrlBuilder::add($member->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['staff.add'], $this->lang['staff.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::add($member->get_id_category()));
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['staff.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['staff.edit'], $this->lang['staff.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::edit($member->get_id()));

			$categories = array_reverse(StaffService::get_categories_manager()->get_parents($member->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $member->get_category();
			$breadcrumb->add($member->get_lastname() . ' ' .$member->get_firstname(), StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $member->get_id(), $member->get_rewrited_name()));
			$breadcrumb->add($this->lang['staff.edit'], StaffUrlBuilder::edit($member->get_id()));
		}

		return $response;
	}
}
?>
