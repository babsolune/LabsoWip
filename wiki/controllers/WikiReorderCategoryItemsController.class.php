<?php
/*##################################################
 *                               WikiReorderCategoryItemsController.class.php
 *                            -------------------
 *   begin                : May 25, 2018
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

class WikiReorderCategoryItemsController extends ModuleController
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
			AppContext::get_response()->redirect(WikiUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()), LangLoader::get_message('message.success.position.update', 'status-messages-common'));
		}

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->tpl = new FileTemplate('wiki/WikiReorderCategoryItemsController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = WikiConfig::load();

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. WikiSetup::$wiki_table .' wiki
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = wiki.author_user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
		AND wiki.id_category = :id_category
		ORDER BY order_id ASC', array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->tpl->put_all(array(
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($category_description),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_DOCUMENTS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_DOCUMENT' => $result->get_rows_count() > 1,
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? WikiUrlBuilder::configuration()->rel() : WikiUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'DOCUMENTS_NUMBER' => $result->get_rows_count()
		));

		while ($row = $result->fetch())
		{
			$wiki_document = new Document();
			$wiki_document->set_properties($row);

			$this->tpl->assign_block_vars('documents', $wiki_document->get_array_tpl_vars());
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
					$this->category = WikiService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = WikiService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		$id_category = $this->get_category()->get_id();
		if (!WikiAuthorizationsService::check_authorizations($id_category)->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function update_position(HTTPRequestCustom $request)
	{
		$documents_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($documents_list as $position => $tree)
		{
			WikiService::update_position($tree->id, $position);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module.title']);
		else
			$graphical_environment->set_page_title($this->lang['module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], WikiUrlBuilder::home());

		$categories = array_reverse(WikiService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		$breadcrumb->add($this->lang['wiki.reorder'], WikiUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		return $response;
	}
}
?>
