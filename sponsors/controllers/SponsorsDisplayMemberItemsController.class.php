<?php
/*##################################################
 *                      SponsorsDisplayMemberItemsController.class.php
 *                            -------------------
 *   begin                : May 20, 2018
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

class SponsorsDisplayMemberItemsController extends ModuleController
{
	private $lang;
	private $config;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->view = new FileTemplate('sponsors/SponsorsDisplayTableController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = SponsorsConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();

		$member_items = AppContext::get_current_user()->get_id();

		$this->build_items_listing_view($now, $member_items);
	}

	private function build_items_listing_view(Date $now, $member_items)
	{
		if(!empty($member_items))
		{
			$authorized_categories = SponsorsService::get_authorized_categories($this->get_category()->get_id());

			$condition = 'WHERE id_category IN :authorized_categories
			AND sponsors.author_user_id = :mbr_id
			AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))';
			$parameters = array(
				'authorized_categories' => $authorized_categories,
				'timestamp_now' => $now->get_timestamp(),
				'mbr_id' => AppContext::get_current_user()->get_id()
			);

			$result = PersistenceContext::get_querier()->select('SELECT sponsors.*, member.*
			FROM ' . SponsorsSetup::$sponsors_table . ' sponsors
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = sponsors.author_user_id
			' . $condition . '
			ORDER BY sponsors.partner_level, sponsors.title
			', array_merge($parameters, array(
				'user_id' => AppContext::get_current_user()->get_id()
			)));

			$this->view->put_all(array(
				'C_ITEMS'                => $result->get_rows_count() > 0,
				'C_MORE_THAN_ONE_ITEM'   => $result->get_rows_count() > 1,

				'C_MEMBER'			     => true,
				'C_NO_ITEM_AVAILABLE'    => $result->get_rows_count() == 0,
				'C_MODERATION'           => SponsorsAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
				'C_ONE_ITEM_AVAILABLE'   => $result->get_rows_count() == 1,
				'C_TWO_ITEMS_AVAILABLE'  => $result->get_rows_count() == 2,
				'C_MEMBERSHIP_TERMS'	         => $this->config->are_membership_terms_displayed(),
				'ITEMS_PER_PAGE'         => $this->config->get_items_number_per_page(),
				'ID_CATEGORY'            => $this->get_category()->get_id(),
				'U_MEMBERSHIP_TERMS' 		 => SponsorsUrlBuilder::membership_terms()->rel()
			));

			while($row = $result->fetch())
			{
				$partner = new Partner();
				$partner->set_properties($row);

				$this->view->assign_block_vars('items', $partner->get_array_tpl_vars());
			}
			$result->dispose();
		}
		else
		{
			AppContext::get_response()->redirect(SponsorsUrlBuilder::home());
		}


	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = SponsorsService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = SponsorsService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (!SponsorsAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['sponsors.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['sponsors.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sponsors.module.title'], SponsorsUrlBuilder::home());
		$breadcrumb->add($this->lang['sponsors.member.items'], SponsorsUrlBuilder::display_member_items());

		$categories = array_reverse(SponsorsService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
