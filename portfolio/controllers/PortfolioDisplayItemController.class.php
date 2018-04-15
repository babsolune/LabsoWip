<?php
/*##################################################
 *		       PortfolioDisplayItemController.class.php
 *                            -------------------
 *   begin                : November 29, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
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

class PortfolioDisplayItemController extends ModuleController
{
	private $lang;
	private $tpl;
	private $work;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->check_pending_work($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'portfolio');
		$this->tpl = new FileTemplate('portfolio/PortfolioDisplayItemController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_work()
	{
		if ($this->work === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->work = PortfolioService::get_work('WHERE portfolio.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->work = new Work();
		}
		return $this->work;
	}

	private function check_pending_work(HTTPRequestCustom $request)
	{
		if (!$this->work->is_published())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), PortfolioUrlBuilder::display_item($this->work->get_category()->get_id(), $this->work->get_category()->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title())->rel()))
			{
				$this->work->set_views_number($this->work->get_views_number() + 1);
				PortfolioService::update_views_number($this->work);
			}
		}
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$current_page = $request->get_getint('page', 1);
		$config = PortfolioConfig::load();
		$comments_config = new PortfolioComments();
		$notation_config = new PortfolioNotation();

		$this->category = $this->work->get_category();

		$work_contents = $this->work->get_contents();

		//If work doesn't begin with a page, we insert one
		if (TextHelper::substr(trim($work_contents), 0, 6) != '[page]')
		{
			$work_contents = '[page]&nbsp;[/page]' . $work_contents;
		}

		//Removing [page] bbcode
		$work_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $work_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		//Retrieving pages
		preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $work_contents, $pages_array);

		$page_nbr = count($pages_array[1]);

		if ($page_nbr > 1)
			$this->build_form($pages_array, $current_page);

		$this->build_sources_view();
		$this->build_carousel_view();
		$this->build_keywords_view();
		$this->build_suggested_items($this->work);
		$this->build_navigation_links($this->work);

		$page_name = (isset($pages_array[1][$current_page-1]) && $pages_array[1][$current_page-1] != '&nbsp;') ? $pages_array[1][($current_page-1)] : '';

		$this->tpl->put_all(array_merge($this->work->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $comments_config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $notation_config->is_notation_enabled(),
			'KERNEL_NOTATION'    => NotationService::display_active_image($this->work->get_notation()),
			'CONTENTS'           => isset($work_contents_clean[$current_page-1]) ? FormatingHelper::second_parse($work_contents_clean[$current_page-1]) : '',
			'PAGE_NAME'          => $page_name,
			'U_EDIT_ITEM'     	 => $page_name !== '' ? PortfolioUrlBuilder::edit_item($this->work->get_id(), $current_page)->rel() : PortfolioUrlBuilder::edit_item($this->work->get_id())->rel()
		)));

		$this->build_pages_pagination($current_page, $page_nbr, $pages_array);

		//Affichage commentaires
		if ($comments_config->are_comments_enabled())
		{
			$comments_topic = new PortfolioCommentsTopic($this->work);
			$comments_topic->set_id_in_module($this->work->get_id());
			$comments_topic->set_url(PortfolioUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title()));

			$this->tpl->put('COMMENTS', $comments_topic->display());
		}
	}

	private function build_pages_pagination($current_page, $page_nbr, $pages_array)
	{
		$this->tpl->put_all(array(
			'C_FIRST_PAGE' => $current_page >= 0 && $current_page<= 1,
		));

		if ($page_nbr > 1)
		{
			$pagination = $this->get_pagination($page_nbr, $current_page);

			if ($current_page > 1 && $current_page <= $page_nbr)
			{
				$previous_page = PortfolioUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title())->rel() . ($current_page - 1);

				$this->tpl->put_all(array(
					'U_PREVIOUS_PAGE' => $previous_page,
					'L_PREVIOUS_TITLE' => $pages_array[1][$current_page-2]
				));
			}

			if ($current_page > 0 && $current_page < $page_nbr)
			{
				$next_page = PortfolioUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title())->rel() . ($current_page + 1);

				$this->tpl->put_all(array(
					'U_NEXT_PAGE' => $next_page,
					'L_NEXT_TITLE' => $pages_array[1][$current_page]
				));
			}

			$this->tpl->put_all(array(
				'C_PAGINATION' => true,
				'C_PREVIOUS_PAGE' => ($current_page != 1) ? true : false,
				'C_NEXT_PAGE' => ($current_page != $page_nbr) ? true : false,
				'ITEMS_PAGINATION' => $pagination->display()
			));
		}
	}

	private function build_form($pages_array, $current_page)
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('pages', array('description' => $this->lang['portfolio.summary']));

		$form->add_fieldset($fieldset);

		$work_pages = $this->list_work_pages($pages_array);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('work_pages', '', $current_page, $work_pages,
			array('class' => 'summary', 'events' => array('change' => 'document.location = "' . PortfolioUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title())->rel() . '" + HTMLForms.getField("work_pages").getValue();'))
		));

		$this->tpl->put('FORM', $form->display());
	}

	private function list_work_pages($pages_array)
	{
		$options = array();

		$i = 1;
		foreach ($pages_array[1] as $page_name)
		{
			$options[] = new FormFieldSelectChoiceOption($page_name, $i++);
		}

		return $options;
	}

	private function build_sources_view()
	{
		$sources = $this->work->get_sources();
		$nbr_sources = count($sources);
		$this->tpl->put('C_SOURCES', $nbr_sources > 0);

		$i = 1;
		foreach ($sources as $name => $url)
		{
			$this->tpl->assign_block_vars('sources', array(
				'C_SEPARATOR' => $i < $nbr_sources,
				'NAME' => $name,
				'URL' => $url,
			));
			$i++;
		}
	}

	private function build_carousel_view()
	{
		$carousel = $this->work->get_carousel();
		$nbr_pictures = count($carousel);
		$this->tpl->put('C_CAROUSEL', $nbr_pictures > 0);


		$i = 1;
		foreach ($carousel as $name => $url)
		{
			if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
				$ptr = false;
			else
				$ptr = true;

			$this->tpl->assign_block_vars('carousel', array(
				'C_PTR' => $ptr,
				'NAME' => $name,
				'URL' => $url,
			));
			$i++;
		}
	}

	private function build_keywords_view()
	{
		$keywords = $this->work->get_keywords();
		$nbr_keywords = count($keywords);
		$this->tpl->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => PortfolioUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function build_suggested_items(Work $work)
	{
		$now = new Date();

		$result = PersistenceContext::get_querier()->select('
		SELECT id, title, category_id, rewrited_title, thumbnail_url,
		(2 * FT_SEARCH_RELEVANCE(title, :search_content) + FT_SEARCH_RELEVANCE(contents, :search_content) / 3) AS relevance
		FROM ' . PortfolioSetup::$portfolio_table . '
		WHERE (FT_SEARCH(title, :search_content) OR FT_SEARCH(contents, :search_content)) AND id <> :excluded_id
		AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))
		ORDER BY relevance DESC LIMIT 0, :limit_nb', array(
			'excluded_id' => $work->get_id(),
			'search_content' => $work->get_title() .','. $work->get_contents(),
			'timestamp_now' => $now->get_timestamp(),
			'limit_nb' => (int) PortfolioConfig::load()->get_suggested_items_nb()
		));

		$this->tpl->put_all(array(
			'C_SUGGESTED_ITEMS' => $result->get_rows_count() > 0 && PortfolioConfig::load()->get_enabled_items_suggestions(),
			'SUGGESTED_COLUMNS' => PortfolioConfig::load()->get_cols_number_displayed_per_line()
		));

		while ($row = $result->fetch())
		{
			if(filter_var($row['thumbnail_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
				$ptr = false;
			else
				$ptr = true;

			$this->tpl->assign_block_vars('suggested_items', array(
				'C_PTR' => $ptr,
				'C_HAS_THUMBNAIL' => !empty($row['thumbnail_url']),
				'TITLE' => $row['title'],
				'THUMBNAIL' => $row['thumbnail_url'],
				'U_ITEM' => PortfolioUrlBuilder::display_item($row['category_id'], PortfolioService::get_categories_manager()->get_categories_cache()->get_category($row['category_id'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel()
			));
		}
		$result->dispose();
	}

	private function build_navigation_links(Work $work)
	{
		$now = new Date();
		$timestamp_work = $work->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, title, category_id, rewrited_title, thumbnail_url, \'PREVIOUS\' as type
		FROM '. PortfolioSetup::$portfolio_table .'
		WHERE (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0))) AND creation_date < :timestamp_work AND category_id IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, title, category_id, rewrited_title, thumbnail_url, \'NEXT\' as type
		FROM '. PortfolioSetup::$portfolio_table .'
		WHERE (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0))) AND creation_date > :timestamp_work AND category_id IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp_work' => $timestamp_work,
			'authorized_categories' => array($work->get_category_id())
		));

		$this->tpl->put_all(array(
			'C_NAVIGATION_LINKS' => $result->get_rows_count() > 0 && PortfolioConfig::load()->get_enabled_navigation_links(),
		));

		while ($row = $result->fetch())
		{
			if(filter_var($row['thumbnail_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
				$ptr = false;
			else
				$ptr = true;

			$this->tpl->put_all(array(
				'C_'. $row['type'] .'_ITEM' => true,
				'C_' . $row['type'] . '_PTR' => $ptr,
				'C_' . $row['type'] . '_HAS_THUMBNAIL' => !empty($row['thumbnail_url']),
				$row['type'] . '_ITEM_TITLE' => $row['title'],
				$row['type'] . '_THUMBNAIL' => $row['thumbnail_url'],
				'U_'. $row['type'] .'_ITEM' => PortfolioUrlBuilder::display_item($row['category_id'], PortfolioService::get_categories_manager()->get_categories_cache()->get_category($row['category_id'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel(),
			));
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		$work = $this->get_work();

		$current_user = AppContext::get_current_user();
		$not_authorized = !PortfolioAuthorizationsService::check_authorizations($work->get_category_id())->moderation() && !PortfolioAuthorizationsService::check_authorizations($work->get_category_id())->write() && (!PortfolioAuthorizationsService::check_authorizations($work->get_category_id())->contribution() || $work->get_author_user()->get_id() != $current_user->get_id());

		switch ($work->get_publication_state())
		{
			case Work::PUBLISHED_NOW:
				if (!PortfolioAuthorizationsService::check_authorizations($work->get_category_id())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Work::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Work::PUBLICATION_DATE:
				if (!$work->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function get_pagination($page_nbr, $current_page)
	{
		$pagination = new ModulePagination($current_page, $page_nbr, 1, Pagination::LIGHT_PAGINATION);
		$pagination->set_url(PortfolioUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title(), '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->work->get_title(), $this->lang['portfolio.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->work->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PortfolioUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['portfolio.module.title'], PortfolioUrlBuilder::home());

		$categories = array_reverse(PortfolioService::get_categories_manager()->get_parents($this->work->get_category_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PortfolioUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->work->get_title(), PortfolioUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->work->get_id(), $this->work->get_rewrited_title()));

		return $response;
	}
}
?>
