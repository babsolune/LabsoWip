<?php
/*##################################################
 *                               ClubsDisplayPendingClubsController.class.php
 *                            -------------------
 *   begin                : June 23, 2017
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

class ClubsDisplayPendingClubsController extends ModuleController
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
		$this->lang = LangLoader::get('common', 'clubs');
		$this->tpl = new FileTemplate('clubs/ClubsDisplaySeveralClubsController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = ClubsConfig::load();
		$authorized_categories = ClubsService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!ClubsAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND approbation_type = 0';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT clubs.*, member.*
		FROM '. ClubsSetup::$clubs_table .' clubs
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = clubs.author_user_id
		' . $condition . '
		ORDER BY clubs.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $config->get_columns_number_per_line();

		$this->tpl->put_all(array(
			'C_CLUBS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_CLUB' => $result->get_rows_count() > 1,
			'C_PENDING' => true,
			'C_CATEGORY_DISPLAYED_TABLE' => $config->is_category_displayed_table(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));

		while ($row = $result->fetch())
		{
			$club = new Club();
			$club->set_properties($row);

			$this->tpl->assign_block_vars('clubs', array_merge($club->get_array_tpl_vars()));
		}
		$result->dispose();
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$clubs_number = ClubsService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $clubs_number, (int)ClubsConfig::load()->get_items_number_per_page());
		$pagination->set_url(ClubsUrlBuilder::display_pending('%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!(ClubsAuthorizationsService::check_authorizations()->write() || ClubsAuthorizationsService::check_authorizations()->contribution() || ClubsAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['clubs.pending'], $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['clubs.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ClubsUrlBuilder::display_pending(AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], ClubsUrlBuilder::home());
		$breadcrumb->add($this->lang['clubs.pending'], ClubsUrlBuilder::display_pending());

		return $response;
	}
}
?>
