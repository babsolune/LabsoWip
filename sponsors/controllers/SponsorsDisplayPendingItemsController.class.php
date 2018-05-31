<?php
/*##################################################
 *		    SponsorsDisplayPendingItemsController.class.php
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

class SponsorsDisplayPendingItemsController extends ModuleController
{
	private $lang;
	private $view;
	private $form;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_view($request);
		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->view = new FileTemplate('sponsors/SponsorsDisplayTableController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view($request)
	{
		$now = new Date();
		$authorized_categories = SponsorsService::get_authorized_categories(Category::ROOT_CATEGORY);
		$this->config = SponsorsConfig::load();

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!SponsorsAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publication_start_date > :timestamp_now OR (publication_end_date != 0 AND publication_end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);

		$result = PersistenceContext::get_querier()->select('SELECT sponsors.*, member.*
		FROM '. SponsorsSetup::$sponsors_table .' sponsors
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = sponsors.author_user_id
		' . $condition . '
		ORDER BY sponsors.partner_level, sponsors.title
		', array_merge($parameters, array(
		)));

		$nbr_items_pending = $result->get_rows_count();

		$this->view->put_all(array(
			'C_ITEMS'                => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_ITEM'   => $result->get_rows_count() > 1,
			'C_PENDING'              => true,
			'C_NO_ITEM_AVAILABLE'    => $nbr_items_pending == 0,
			'ITEMS_PER_PAGE'         => $this->config->get_items_number_per_page()
		));

		if ($nbr_items_pending > 0)
		{
			while($row = $result->fetch())
			{
				$partner = new Partner();
				$partner->set_properties($row);

				$this->view->assign_block_vars('items', $partner->get_array_tpl_vars());
			}
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		if (!(SponsorsAuthorizationsService::check_authorizations()->write() || SponsorsAuthorizationsService::check_authorizations()->contribution() || SponsorsAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sponsors.pending.items'], $this->lang['sponsors.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['sponsors.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::display_pending_items(AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sponsors.module.title'], SponsorsUrlBuilder::home());
		$breadcrumb->add($this->lang['sponsors.pending.items'], SponsorsUrlBuilder::display_pending_items(AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));

		return $response;
	}
}
?>
