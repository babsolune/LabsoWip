<?php
/*##################################################
 *		       SponsorsDisplayItemController.class.php
 *                            -------------------
 *   begin                : May 20, 2018
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

class SponsorsDisplayItemController extends ModuleController
{
	private $lang;
	private $tpl;
	private $partner;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->check_pending_partner($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$this->tpl = new FileTemplate('sponsors/SponsorsDisplayItemController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$this->category = $this->partner->get_category();

		$this->build_suggested_items($this->partner);
		$this->build_navigation_links($this->partner);

		$this->tpl->put_all(array_merge($this->partner->get_array_tpl_vars(), array(
			'CONTENTS'           => FormatingHelper::second_parse($this->partner->get_contents()),
			'U_EDIT_ITEM'     	 => SponsorsUrlBuilder::edit_item($this->partner->get_id())->rel()
		)));
	}

	private function get_partner()
	{
		if ($this->partner === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->partner = SponsorsService::get_partner('WHERE sponsors.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->partner = new Partner();
		}
		return $this->partner;
	}

	private function check_pending_partner(HTTPRequestCustom $request)
	{
		if (!$this->partner->is_published())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), SponsorsUrlBuilder::display_item($this->partner->get_category()->get_id(), $this->partner->get_category()->get_rewrited_name(), $this->partner->get_id(), $this->partner->get_rewrited_title())->rel()))
			{
				$this->partner->set_views_number($this->partner->get_views_number() + 1);
				SponsorsService::update_views_number($this->partner);
			}
		}
	}

	private function build_suggested_items(Partner $partner)
	{
		$now = new Date();

		$result = PersistenceContext::get_querier()->select('
		SELECT id, title, id_category, rewrited_title, thumbnail_url,
		(2 * FT_SEARCH_RELEVANCE(title, :search_content) + FT_SEARCH_RELEVANCE(contents, :search_content) / 3) AS relevance
		FROM ' . SponsorsSetup::$sponsors_table . '
		WHERE (FT_SEARCH(title, :search_content) OR FT_SEARCH(contents, :search_content)) AND id <> :excluded_id
		AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))
		ORDER BY relevance DESC LIMIT 0, :limit_nb', array(
			'excluded_id' => $partner->get_id(),
			'search_content' => $partner->get_title() .','. $partner->get_contents(),
			'timestamp_now' => $now->get_timestamp(),
			'limit_nb' => (int) SponsorsConfig::load()->get_suggested_items_nb()
		));

		$this->tpl->put_all(array(
			'C_SUGGESTED_ITEMS' => $result->get_rows_count() > 0 && SponsorsConfig::load()->get_enabled_items_suggestions()
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
				'U_ITEM' => SponsorsUrlBuilder::display_item($row['id_category'], SponsorsService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel()
			));
		}
		$result->dispose();
	}

	private function build_navigation_links(Partner $partner)
	{
		$now = new Date();
		$timestamp_partner = $partner->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, \'PREVIOUS\' as type
		FROM '. SponsorsSetup::$sponsors_table .'
		WHERE (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0))) AND creation_date < :timestamp_partner AND id_category IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, \'NEXT\' as type
		FROM '. SponsorsSetup::$sponsors_table .'
		WHERE (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0))) AND creation_date > :timestamp_partner AND id_category IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp_partner' => $timestamp_partner,
			'authorized_categories' => array($partner->get_id_category())
		));

		$this->tpl->put_all(array(
			'C_NAVIGATION_LINKS' => $result->get_rows_count() > 0 && SponsorsConfig::load()->get_enabled_navigation_links(),
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
				'U_'. $row['type'] .'_ITEM' => SponsorsUrlBuilder::display_item($row['id_category'], SponsorsService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel(),
			));
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		$partner = $this->get_partner();

		$current_user = AppContext::get_current_user();
		$not_authorized = !SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->moderation() && !SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->write() && (!SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->contribution() || $partner->get_author_user()->get_id() != $current_user->get_id());

		switch ($partner->get_publication_state())
		{
			case Partner::PUBLISHED_NOW:
				if (!SponsorsAuthorizationsService::check_authorizations($partner->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Partner::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Partner::PUBLICATION_DATE:
				if (!$partner->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->partner->get_title(), $this->lang['sponsors.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->partner->get_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SponsorsUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->partner->get_id(), $this->partner->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sponsors.module.title'], SponsorsUrlBuilder::home());

		$categories = array_reverse(SponsorsService::get_categories_manager()->get_parents($this->partner->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->partner->get_title(), SponsorsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->partner->get_id(), $this->partner->get_rewrited_title()));

		return $response;
	}
}
?>
