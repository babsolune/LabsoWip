<?php
/*##################################################
 *                               StaffDisplayHomeController.class.php
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

class StaffDisplayHomeController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'staff');
		$this->tpl = new FileTemplate('staff/StaffDisplayHomeController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = StaffConfig::load();

	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$subcategories = StaffService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), StaffService::get_authorized_categories($this->get_category()->get_id()));

		$this->tpl->put_all(array(
		));

		$authorized_categories = StaffService::get_authorized_categories(Category::ROOT_CATEGORY);

		$result_cat = PersistenceContext::get_querier()->select('SELECT staff_cat.*
		FROM '. StaffSetup::$staff_cats_table .' staff_cat
		WHERE staff_cat.id IN :authorized_categories
		ORDER BY staff_cat.id ASC', array(
			'authorized_categories' => $authorized_categories
		));

		$this->tpl->put_all(array(
			'C_AVATARS_ALLOWED' => $this->config->are_avatars_shown(),
			'C_ROOT_DESCRIPTION' => !empty($this->config->get_root_category_description()),
			'ROOT_DESCRIPTION' => $this->config->get_root_category_description(),
			'C_MODERATE' => AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)
		));

		while ($row_cat = $result_cat->fetch())
		{
			$this->tpl->assign_block_vars('staff', array(
				'ID' => $row_cat['id'],
				'SUB_ORDER' => $row_cat['c_order'],
				'ID_PARENT' => $row_cat['id_parent'],
				'CATEGORY_NAME' => $row_cat['name'],
				'U_CATEGORY' => StaffUrlBuilder::display_category($row_cat['id'], $row_cat['rewrited_name'])->rel()
			));

			$id_cat = $row_cat['id'];

			$result = PersistenceContext::get_querier()->select('SELECT staff.*, member.*
			FROM '. StaffSetup::$staff_table .' staff
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = staff.author_user_id
			WHERE staff.id_category = :id_cat
			AND staff.publication = 1
			ORDER BY staff.group_leader DESC, staff.order_id', array(
				'user_id' => AppContext::get_current_user()->get_id(),
				'id_cat' => $id_cat,
				'timestamp_now' => $now->get_timestamp()
			));

			while ($row = $result->fetch())
			{
				$adherent = new Adherent();
				$adherent->set_properties($row);

				$this->tpl->assign_block_vars('staff.items', $adherent->get_array_tpl_vars());
			}
			$result->dispose();
		}
		$result_cat->dispose();
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = StaffService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = StaffService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if ((!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || !StaffAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!StaffAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->lang['staff.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['staff.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['staff.module.title'], StaffUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->tpl;
	}
}
?>
