<?php
/*##################################################
 *		               PalmaresDisplayPalmaresController.class.php
 *                            -------------------
 *   begin                : February 15, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class PalmaresDisplayPalmaresController extends ModuleController
{	
	private $lang;
	private $tpl;
	
	private $palmares;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->count_number_view($request);
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'palmares');
		$this->tpl = new FileTemplate('palmares/PalmaresDisplayPalmaresController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function get_palmares()
	{
		if ($this->palmares === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->palmares = PalmaresService::get_palmares('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->palmares = new Palmares();
		}
		return $this->palmares;
	}
	
	private function count_number_view(HTTPRequestCustom $request)
	{
		if (!$this->palmares->is_visible())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), PalmaresUrlBuilder::display_palmares($this->palmares->get_category()->get_id(), $this->palmares->get_category()->get_rewrited_name(), $this->palmares->get_id(), $this->palmares->get_rewrited_name())->rel()))
			{
				$this->palmares->set_number_view($this->palmares->get_number_view() + 1);
				PalmaresService::update_number_view($this->palmares);
			}
		}
	}
	
	private function build_view()
	{
		$palmares = $this->get_palmares();
		$palmares_config = PalmaresConfig::load();
		$comments_config = new PalmaresComments();
		$category = $palmares->get_category();
		
		$this->tpl->put_all(array_merge($palmares->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $comments_config->are_comments_enabled(),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));
		
		if ($comments_config->are_comments_enabled())
		{
			$comments_topic = new PalmaresCommentsTopic($palmares);
			$comments_topic->set_id_in_module($palmares->get_id());
			$comments_topic->set_url(PalmaresUrlBuilder::display_palmares($category->get_id(), $category->get_rewrited_name(), $palmares->get_id(), $palmares->get_rewrited_name()));
			
			$this->tpl->put_all(array(
				'COMMENTS' => $comments_topic->display()
			));
		}
		
		$this->build_sources_view($palmares);
		$this->build_keywords_view($palmares);
		$this->build_suggested_palmares($palmares);
		$this->build_navigation_links($palmares);
	}
	
	private function build_sources_view(Palmares $palmares)
	{
		$sources = $palmares->get_sources();
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
	
	private function build_keywords_view(Palmares $palmares)
	{
		$keywords = $palmares->get_keywords();
		$nbr_keywords = count($keywords);
		$this->tpl->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{	
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => PalmaresUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}
	
	
	private function build_suggested_palmares(Palmares $palmares)
	{
		$now = new Date();
		
		$result = PersistenceContext::get_querier()->select('
		SELECT id, name, id_category, rewrited_name, 
		(2 * FT_SEARCH_RELEVANCE(name, :search_content) + FT_SEARCH_RELEVANCE(contents, :search_content) / 3) AS relevance
		FROM ' . PalmaresSetup::$palmares_table . '
		WHERE (FT_SEARCH(name, :search_content) OR FT_SEARCH(contents, :search_content)) AND id <> :excluded_id
		AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))
		ORDER BY relevance DESC LIMIT 0, 10', array(
			'excluded_id' => $palmares->get_id(),
			'search_content' => $palmares->get_name() .','. $palmares->get_contents(),
			'timestamp_now' => $now->get_timestamp()
		));
		
		$this->tpl->put('C_SUGGESTED_PALMARES', ($result->get_rows_count() > 0 && PalmaresConfig::load()->get_palmares_suggestions_enabled()));
		
		while ($row = $result->fetch())
		{
			$this->tpl->assign_block_vars('suggested', array(
				'NAME' => $row['name'],
				'URL' => PalmaresUrlBuilder::display_palmares($row['id_category'], PalmaresService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel()
			));
		}
		$result->dispose();
	}
	
	private function build_navigation_links(Palmares $palmares)
	{
		$now = new Date();
		$timestamp_palmares = $palmares->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, name, id_category, rewrited_name, \'PREVIOUS\' as type
		FROM '. PalmaresSetup::$palmares_table .'
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date < :timestamp_palmares AND id_category IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, name, id_category, rewrited_name, \'NEXT\' as type
		FROM '. PalmaresSetup::$palmares_table .'
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date > :timestamp_palmares AND id_category IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp_palmares' => $timestamp_palmares,
			'authorized_categories' => array($palmares->get_id_cat())
		));
		
		while ($row = $result->fetch())
		{
			$this->tpl->put_all(array(
				'C_PALMARES_NAVIGATION_LINKS' => true,
				'C_'. $row['type'] .'_PALMARES' => true,
				$row['type'] . '_PALMARES' => $row['name'],
				'U_'. $row['type'] .'_PALMARES' => PalmaresUrlBuilder::display_palmares($row['id_category'], PalmaresService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
			));
		}
		$result->dispose();
	}
	
	private function check_authorizations()
	{
		$palmares = $this->get_palmares();
		
		$current_user = AppContext::get_current_user();
		$not_authorized = !PalmaresAuthorizationsService::check_authorizations($palmares->get_id_cat())->moderation() && !PalmaresAuthorizationsService::check_authorizations($palmares->get_id_cat())->write() && (!PalmaresAuthorizationsService::check_authorizations($palmares->get_id_cat())->contribution() || $palmares->get_author_user()->get_id() != $current_user->get_id());
		
		switch ($palmares->get_approbation_type()) {
			case Palmares::APPROVAL_NOW:
				if (!PalmaresAuthorizationsService::check_authorizations($palmares->get_id_cat())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Palmares::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Palmares::APPROVAL_DATE:
				if (!$palmares->is_visible() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$category = $this->get_palmares()->get_category();
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_palmares()->get_name(), $this->lang['palmares']);
		$graphical_environment->get_seo_meta_data()->set_description($this->get_palmares()->get_short_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PalmaresUrlBuilder::display_palmares($category->get_id(), $category->get_rewrited_name(), $this->get_palmares()->get_id(), $this->get_palmares()->get_rewrited_name()));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['palmares'], PalmaresUrlBuilder::home());
		
		$categories = array_reverse(PalmaresService::get_categories_manager()->get_parents($this->get_palmares()->get_id_cat(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PalmaresUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->get_palmares()->get_name(), PalmaresUrlBuilder::display_palmares($category->get_id(), $category->get_rewrited_name(), $this->get_palmares()->get_id(), $this->get_palmares()->get_rewrited_name()));
		
		return $response;
	}
}
?>
