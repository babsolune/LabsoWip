<?php
/*##################################################
 *		               RadioDisplayRadioController.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class RadioDisplayRadioController extends ModuleController
{
	private $lang;
	private $tpl;

	private $radio;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'radio');
		$this->tpl = new FileTemplate('radio/RadioDisplayRadioController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_radio()
	{
		if ($this->radio === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->radio = RadioService::get_radio('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->radio = new Radio();
		}
		return $this->radio;
	}

	private function build_view()
	{
		$radio = $this->get_radio();
		$radio_config = RadioConfig::load();
		$category = $radio->get_category();

		$this->tpl->put_all(array_merge($radio->get_array_tpl_vars(), array(
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));
	}

	private function check_authorizations()
	{
		$radio = $this->get_radio();

		$current_user = AppContext::get_current_user();
		$not_authorized = !RadioAuthorizationsService::check_authorizations($radio->get_id_cat())->moderation() && !RadioAuthorizationsService::check_authorizations($radio->get_id_cat())->write() && (!RadioAuthorizationsService::check_authorizations($radio->get_id_cat())->contribution() || $radio->get_author_user()->get_id() != $current_user->get_id());

		switch ($radio->get_approbation_type()) {
			case Radio::APPROVAL_NOW:
				if (!RadioAuthorizationsService::check_authorizations($radio->get_id_cat())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Radio::NOT_APPROVAL:
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
		$category = $this->get_radio()->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_radio()->get_name(), $this->lang['radio']);
		$graphical_environment->get_seo_meta_data()->set_description($this->get_radio()->get_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(RadioUrlBuilder::display_radio($category->get_id(), $category->get_rewrited_name(), $this->get_radio()->get_id(), $this->get_radio()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['radio'], RadioUrlBuilder::home());

		$categories = array_reverse(RadioService::get_categories_manager()->get_parents($this->get_radio()->get_id_cat(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), RadioUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->get_radio()->get_name(), RadioUrlBuilder::display_radio($category->get_id(), $category->get_rewrited_name(), $this->get_radio()->get_id(), $this->get_radio()->get_rewrited_name()));

		return $response;
	}
}
?>
