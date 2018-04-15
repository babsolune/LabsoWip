<?php
/*##################################################
 *                               SponsorsDisplayPartnerController.class.php
 *                            -------------------
 *   begin                : September 13, 2017
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

class SponsorsDisplayPartnerController extends ModuleController
{
	private $lang;
	private $tpl;

	private $partner;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->tpl = new FileTemplate('sponsors/SponsorsDisplayPartnerController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_partner()
	{
		if ($this->partner === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->partner = SponsorsService::get_partner('WHERE sponsors.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->partner = new Partner();
		}
		return $this->partner;
	}

	private function build_view()
	{
		$config = SponsorsConfig::load();
		$partner = $this->get_partner();
		$category = $partner->get_category();

		$keywords = $partner->get_keywords();
		$has_keywords = count($keywords) > 0;

		$this->tpl->put_all(array_merge($partner->get_array_tpl_vars(), array(
			'C_KEYWORDS' => $has_keywords,
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		if ($has_keywords)
			$this->build_keywords_view($keywords);
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => SponsorsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$partner = $this->get_partner();

		$current_user = AppContext::get_current_user();
		$not_authorized = !SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->moderation() && !SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->write() && (!SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->contribution() || $partner->get_author_user()->get_id() != $current_user->get_id());

		switch ($partner->get_approbation_type()) {
			case Partner::APPROVAL_NOW:
				if (!SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Partner::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Partner::APPROVAL_DATE:
				if (!$partner->is_visible() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$partner = $this->get_partner();
		$category = $partner->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($partner->get_name(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'],SponsorsUrlBuilder::home());

		$categories = array_reverse(SponsorsService::get_categories_manager()->get_parents($partner->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($partner->get_name(), SponsorsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $partner->get_id(), $partner->get_rewrited_name()));

		return $response;
	}
}
?>
