<?php
/*##################################################
 *		    WikiDisplayPendingItemsController.class.php
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

class WikiDisplayPendingItemsController extends ModuleController
{
	private $lang;
	private $view;
	private $form;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new FileTemplate('wiki/WikiDisplayCategoryController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view($request)
	{
		$now = new Date();
		$authorized_categories = WikiService::get_authorized_categories(Category::ROOT_CATEGORY);
		$config = WikiConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!WikiAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);
		$result = PersistenceContext::get_querier()->select('SELECT wiki.*, member.*
		FROM '. WikiSetup::$wiki_table .' wiki
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = wiki.author_user_id
		' . $condition . '
		ORDER BY order_id ASC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$nbr_wiki_pending = $result->get_rows_count();

		$this->view->put_all(array(
			'C_DOCUMENTS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_DOCUMENT' => $result->get_rows_count() > 1,
			'C_PENDING' => true,
			'C_MOSAIC' => $config->get_display_type() == WikiConfig::DISPLAY_MOSAIC,
			'C_NO_DOCUMENT_AVAILABLE' => $nbr_wiki_pending == 0
		));

		if ($nbr_wiki_pending > 0)
		{
			$number_columns_display_per_line = $config->get_number_cols_display_per_line();

			$this->view->put_all(array(
				'C_DOCUMENTS_FILTERS' => true,
				'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('wiki'),
				'C_NOTATION_ENABLED' => $content_management_config->module_notation_is_enabled('wiki'),
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display(),
				'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
				'NUMBER_COLUMNS' => $number_columns_display_per_line
			));

			while($row = $result->fetch())
			{
				$document = new Document();
				$document->set_properties($row);

				$this->build_keywords_view($document);

				$this->view->assign_block_vars('items', $document->get_array_tpl_vars());
				$this->build_sources_view($document);
			}
		}
		$result->dispose();
	}

	private function build_keywords_view(Document $document)
	{
		$keywords = $document->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WikiUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!(WikiAuthorizationsService::check_authorizations()->write() || WikiAuthorizationsService::check_authorizations()->contribution() || WikiAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$number_wiki = PersistenceContext::get_querier()->count(WikiSetup::$wiki_table, $condition, $parameters);

		$pagination = new ModulePagination($page, $number_wiki, (int)WikiConfig::load()->get_number_items_per_page());
		$pagination->set_url(WikiUrlBuilder::display_pending_items('/%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['wiki.pending_documents'], $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['wiki.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::display_pending_items(AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], WikiUrlBuilder::home());
		$breadcrumb->add($this->lang['wiki.pending_documents'], WikiUrlBuilder::display_pending_items(AppContext::get_request()->get_getint('page', 1)));

		return $response;
	}
}
?>
