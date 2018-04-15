<?php
/*##################################################
 *                               StaffDisplayMemberController.class.php
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

class StaffDisplayMemberController extends ModuleController
{
	private $lang;
	private $tpl;

	private $member;

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
		$this->tpl = new FileTemplate('staff/StaffDisplayMemberController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_member()
	{
		if ($this->member === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->member = StaffService::get_member('WHERE staff.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->member = new Member();
		}
		return $this->member;
	}

	private function build_view()
	{
		$config = StaffConfig::load();
		$member = $this->get_member();
		$category = $member->get_category();

		$this->tpl->put_all(array_merge($member->get_array_tpl_vars(), array(
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));
	}

	private function check_authorizations()
	{
		$member = $this->get_member();

		$current_user = AppContext::get_current_user();
		$not_authorized = !StaffAuthorizationsService::check_authorizations($member->get_id_category())->moderation() && !StaffAuthorizationsService::check_authorizations($member->get_id_category())->write() && (!StaffAuthorizationsService::check_authorizations($member->get_id_category())->contribution() || $member->get_author_user()->get_id() != $current_user->get_id());

		switch ($member->get_approbation_type()) {
			case Member::APPROVAL_NOW:
				if (!StaffAuthorizationsService::check_authorizations($member->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Member::NOT_APPROVAL:
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
		$member = $this->get_member();
		$category = $member->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($member->get_lastname() . ' ' .$member->get_firstname(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $member->get_id(), $member->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'],StaffUrlBuilder::home());

		$categories = array_reverse(StaffService::get_categories_manager()->get_parents($member->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($member->get_lastname() . ' ' .$member->get_firstname(), StaffUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $member->get_id(), $member->get_rewrited_name()));

		return $response;
	}
}
?>
