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
			'C_DISPLAYED_TABLE' => $this->config->is_category_displayed_table(),
			'COLUMNS_NUMBER' => $this->config->get_columns_number_per_line(),
		));

		$result_cat = PersistenceContext::get_querier()->select('SELECT staff_cat.*
		FROM '. StaffSetup::$staff_cats_table .' staff_cat
		WHERE staff_cat.special_authorizations = 0
		ORDER BY staff_cat.id ASC'
		);

		while ($row_cat = $result_cat->fetch())
		{
			$this->tpl->assign_block_vars('staffcats', array(
				'CAT_ID' => $row_cat['id'],
				'CAT_SUB_ORDER' => $row_cat['c_order'],
				'CAT_PARENT_ID' => $row_cat['id_parent'],
				'C_ROOT_COMMISSION' => $row_cat['id_parent'] == 0,
				'C_SUB_COMMISSION' => $row_cat['id_parent'] == 5 || $row_cat['id_parent'] == 10 || $row_cat['id_parent'] == 20,
				'CATEGORY_NAME' => $row_cat['name'],
				'U_CATEGORY' => StaffUrlBuilder::display_category($row_cat['id'], $row_cat['rewrited_name'])->rel()
			));

			$id_cat = $row_cat['id'];

			$result = PersistenceContext::get_querier()->select('SELECT staff.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
			FROM '. StaffSetup::$staff_table .' staff
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = staff.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = staff.id AND com.module_id = \'staff\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = staff.id AND notes.module_name = \'staff\'
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = staff.id AND note.module_name = \'staff\' AND note.user_id = :user_id
			WHERE staff.id_category = :id_cat
			AND staff.approbation_type = 1
			ORDER BY staff.group_leader DESC, staff.role ASC, staff.lastname ASC, staff.firstname ASC', array(
				'user_id' => AppContext::get_current_user()->get_id(),
				'id_cat' => $id_cat,
				'timestamp_now' => $now->get_timestamp()
			));

			while ($row = $result->fetch())
			{
				$member = new Member();
				$member->set_properties($row);

				$this->tpl->assign_block_vars('staffcats.members', $member->get_array_tpl_vars());
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
			if ((!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || $this->config->get_category_display_type() == StaffConfig::DISPLAY_BLOCKS) || !StaffAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
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
			$graphical_environment->set_page_title($this->lang['module_title']);
		else
			$graphical_environment->set_page_title($this->lang['module_title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], StaffUrlBuilder::home());

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
