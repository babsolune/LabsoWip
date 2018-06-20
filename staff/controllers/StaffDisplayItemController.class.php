<?php
/*##################################################
 *                               StaffDisplayItemController.class.php
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

class StaffDisplayItemController extends ModuleController
{
	private $lang;
	private $tpl;

	private $adherent;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'staff');
		$this->tpl = new FileTemplate('staff/StaffDisplayItemController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_view()
	{
		$config = StaffConfig::load();
		$adherent = $this->get_adherent();
		$category = $adherent->get_category();

		$this->tpl->put_all(array_merge($adherent->get_array_tpl_vars(), array(
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		$this->build_email_form();

		// Envoi d'email
		if ($this->submit_button->has_been_submited() && $this->email_form->validate())
		{
			if ($this->send_item_email())
			{
				$this->tpl->put('MSG', MessageHelper::display($this->lang['staff.message.success.email'], MessageHelper::SUCCESS));
				$this->tpl->put('C_ITEM_EMAIL_SENT', true);
			}
			else
				$this->tpl->put('MSG', MessageHelper::display($this->lang['staff.message.error.email'], MessageHelper::ERROR, 5));
		}

		$this->tpl->put('EMAIL_FORM', $this->email_form->display());
	}

	private function build_email_form()
	{
		$adherent_name = $this->adherent->get_firstname() . ' ' . $this->adherent->get_lastname();

		$email_form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('send_a_mail', $this->lang['email.adherent.contact'], array('description' => $adherent_name));
		$email_form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('subject', $this->lang['email.subject'], '',
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldTextEditor('sender_name', $this->lang['email.sender.name'], AppContext::get_current_user()->get_display_name(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldMailEditor('sender_email', $this->lang['email.sender.email'], AppContext::get_current_user()->get_email(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('sender_message', $this->lang['email.sender.message'], '',
			array('required' => true)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$email_form->add_button($this->submit_button);
		$email_form->add_button(new FormButtonReset());

		$this->email_form = $email_form;
	}

	private function send_item_email()
	{
		$email_message = '';
		$email_subject = $this->email_form->get_value('subject');
		$email_sender_name = $this->email_form->get_value('sender_name');
		$email_sender_email = $this->email_form->get_value('sender_email');
		$email_message = $this->email_form->get_value('sender_message');
		$email_recipient_email = $this->get_adherent()->get_item_email();

		$email = new Mail();
		$email->set_sender(MailServiceConfig::load()->get_default_mail_sender(), $this->lang['staff.module.title']);
		$email->set_reply_to($email_sender_email, $email_sender_name);
		$email->set_subject($email_subject);
		$email->set_content(TextHelper::html_entity_decode($email_message));
		$email->add_recipient($email_recipient_email);

		$send_email = AppContext::get_mail_service();

		return $send_email->try_to_send($email);
	}

	private function get_adherent()
	{
		if ($this->adherent === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->adherent = StaffService::get_adherent('WHERE staff.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->adherent = new Adherent();
		}
		return $this->adherent;
	}

	private function check_authorizations()
	{
		$adherent = $this->get_adherent();

		$current_user = AppContext::get_current_user();
		$not_authorized = !StaffAuthorizationsService::check_authorizations($adherent->get_id_category())->moderation() && !StaffAuthorizationsService::check_authorizations($adherent->get_id_category())->write() && (!StaffAuthorizationsService::check_authorizations($adherent->get_id_category())->contribution() || $adherent->get_author_user()->get_id() != $current_user->get_id());

		switch ($adherent->is_published()) {
			case Adherent::PUBLISHED:
				if (!StaffAuthorizationsService::check_authorizations($adherent->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Adherent::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function generate_response()
	{
		$adherent = $this->get_adherent();
		$category = $adherent->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($adherent->get_lastname() . ' ' .$adherent->get_firstname(), $this->lang['staff.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $adherent->get_id(), $adherent->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['staff.module.title'],StaffUrlBuilder::home());

		$categories = array_reverse(StaffService::get_categories_manager()->get_parents($adherent->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($adherent->get_lastname() . ' ' .$adherent->get_firstname(), StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $adherent->get_id(), $adherent->get_rewrited_name()));

		return $response;
	}
}
?>
