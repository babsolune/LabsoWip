<?php
/*##################################################
 *                               StaffDisplayPendingItemsController.class.php
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

class StaffDisplayPendingItemsController extends ModuleController
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
		$this->lang = LangLoader::get('common', 'staff');
		$this->tpl = new FileTemplate('staff/StaffDisplayCategoryController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = StaffConfig::load();
		$authorized_categories = StaffService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!StaffAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND publication = 0';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT staff.*, member.*
			FROM '. StaffSetup::$staff_table .' staff
			LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = staff.author_user_id
			' . $condition . '
			ORDER BY staff.group_leader DESC, staff.lastname ASC, staff.firstname ASC', array_merge($parameters, array(
				'user_id' => AppContext::get_current_user()->get_id()
		)));

		$this->tpl->put_all(array(
			'C_ADHERENTS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_ADHERENT' => $result->get_rows_count() > 1,
			'C_PENDING' => true,
			'C_AVATARS_ALLOWED' => $config->are_avatars_shown(),
			'TABLE_COLSPAN' => 3,
			'C_MODERATE' => AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)
		));

		while ($row = $result->fetch())
		{
			$adherent = new Adherent();
			$adherent->set_properties($row);

			$this->tpl->assign_block_vars('items', array_merge($adherent->get_array_tpl_vars()));
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		if (!(StaffAuthorizationsService::check_authorizations()->write() || StaffAuthorizationsService::check_authorizations()->contribution() || StaffAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['staff.pending'], $this->lang['staff.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['staff.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::display_pending());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['staff.module.title'], StaffUrlBuilder::home());
		$breadcrumb->add($this->lang['staff.pending'], StaffUrlBuilder::display_pending());

		return $response;
	}
}
?>