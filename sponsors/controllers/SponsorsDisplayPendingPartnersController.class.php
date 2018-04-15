<?php
/*##################################################
 *                               SponsorsDisplayPendingPartnersController.class.php
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

class SponsorsDisplayPendingPartnersController extends ModuleController
{
	private $tpl;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response();
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->tpl = new FileTemplate('sponsors/SponsorsDisplaySeveralPartnersController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = SponsorsConfig::load();
		$authorized_categories = SponsorsService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!SponsorsAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND approbation_type = 0';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT sponsors.*, member.*
		FROM '. SponsorsSetup::$sponsors_table .' sponsors
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = sponsors.author_user_id
		' . $condition . '
		ORDER BY sponsors.partner_type DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $config->get_columns_number_per_line();

		$this->tpl->put_all(array(
			'C_PARTNERS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_PARTNER' => $result->get_rows_count() > 1,
			'C_PENDING' => true,
			'C_CATEGORY_DISPLAYED_SUMMARY' => $config->is_category_displayed_summary(),
			'C_CATEGORY_DISPLAYED_TABLE' => $config->is_category_displayed_table(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
		));

		while ($row = $result->fetch())
		{
			$partner = new Partner();
			$partner->set_properties($row);

			$keywords = $partner->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->tpl->assign_block_vars('partners', array_merge($partner->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));

			if ($has_keywords)
				$this->build_keywords_view($keywords);
		}
		$result->dispose();
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$partners_number = SponsorsService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $partners_number, (int)SponsorsConfig::load()->get_items_number_per_page());
		$pagination->set_url(SponsorsUrlBuilder::display_pending('%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('partners.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => SponsorsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
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
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sponsors.pending'], $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['sponsors.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::display_pending(AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], SponsorsUrlBuilder::home());
		$breadcrumb->add($this->lang['sponsors.pending'], SponsorsUrlBuilder::display_pending());

		return $response;
	}
}
?>
