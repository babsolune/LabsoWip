<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
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
