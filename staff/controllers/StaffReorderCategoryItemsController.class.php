<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

class StaffReorderCategoryItemsController extends ModuleController
{
	private $lang;
	private $tpl;

	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			AppContext::get_response()->redirect(StaffUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()), LangLoader::get_message('message.success.position.update', 'status-messages-common'));
		}

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'staff');
		$this->tpl = new FileTemplate('staff/StaffReorderCategoryItemsController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = StaffConfig::load();

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. StaffSetup::$staff_table .' staff
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = staff.author_user_id
		WHERE publication = 1
		AND staff.id_category = :id_category
		ORDER BY order_id ASC', array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->tpl->put_all(array(
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($category_description),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_ADHERENTS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_ADHERENT' => $result->get_rows_count() > 1,
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? StaffUrlBuilder::configuration()->rel() : StaffUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'ADHERENTS_NUMBER' => $result->get_rows_count()
		));

		while ($row = $result->fetch())
		{
			$staff_adherent = new Adherent();
			$staff_adherent->set_properties($row);

			$this->tpl->assign_block_vars('items', $staff_adherent->get_array_tpl_vars());
		}
		$result->dispose();
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
		$id_category = $this->get_category()->get_id();
		if (!StaffAuthorizationsService::check_authorizations($id_category)->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function update_position(HTTPRequestCustom $request)
	{
		$adherents_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($adherents_list as $position => $tree)
		{
			StaffService::update_position($tree->id, $position);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['staff.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['staff.module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(StaffUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['staff.module.title'], StaffUrlBuilder::home());

		$categories = array_reverse(StaffService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), StaffUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		$breadcrumb->add($this->lang['staff.reorder'], StaffUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		return $response;
	}
}
?>
