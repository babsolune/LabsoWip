<?php
/*##################################################
 *		               PalmaresDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : February 20, 2013
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
class PalmaresDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;
	
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'palmares');
		$this->tpl = new FileTemplate('palmares/PalmaresDisplaySeveralPalmaresController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = PalmaresConfig::load();
	}
	
	private function build_view()
	{
		$now = new Date();
		$authorized_categories = PalmaresService::get_authorized_categories($this->get_category()->get_id());
		
		$comments_config = new PalmaresComments();

		$condition = 'WHERE id_category IN :authorized_categories
		AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);
		
		$result = PersistenceContext::get_querier()->select('SELECT palmares.*, member.*
		FROM '. PalmaresSetup::$palmares_table .' palmares
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = palmares.author_user_id
		' . $condition . '
		ORDER BY top_list_enabled DESC, palmares.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));
		
		$number_columns_display_palmares = $this->config->get_number_columns_display_palmares();
		$this->tpl->put_all(array(
			'C_CATEGORY' => true,
			'C_DISPLAY_BLOCK_TYPE' => $this->config->get_display_type() == PalmaresConfig::DISPLAY_BLOCK,
			'C_DISPLAY_LIST_TYPE' => $this->config->get_display_type() == PalmaresConfig::DISPLAY_LIST,
			'C_DISPLAY_CONDENSED_CONTENT' => $this->config->get_display_condensed_enabled(),
			'C_COMMENTS_ENABLED' => $comments_config->are_comments_enabled(),
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? PalmaresUrlBuilder::configuration()->rel() : PalmaresUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			
			'C_PALMARES_NO_AVAILABLE' => $result->get_rows_count() == 0,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			
			'C_SEVERAL_COLUMNS' => $number_columns_display_palmares > 1,
			'NUMBER_COLUMNS' => $number_columns_display_palmares
		));

		while ($row = $result->fetch())
		{
			$palmares = new Palmares();
			$palmares->set_properties($row);
			
			$this->tpl->assign_block_vars('palmares', $palmares->get_array_tpl_vars());
			$this->build_sources_view($palmares);
		}
		$result->dispose();
	}
	
	private function build_sources_view(Palmares $palmares)
	{
		$sources = $palmares->get_sources();
		$nbr_sources = count($sources);
		if ($nbr_sources)
		{
			$this->tpl->put('palmares.C_SOURCES', $nbr_sources > 0);
			
			$i = 1;
			foreach ($sources as $name => $url)
			{
				$this->tpl->assign_block_vars('palmares.sources', array(
					'C_SEPARATOR' => $i < $nbr_sources,
					'NAME' => $name,
					'URL' => $url,
				));
				$i++;
			}
		}
	}
	
	private function get_pagination($condition, $parameters, $page)
	{
		$number_palmares = PersistenceContext::get_querier()->count(PalmaresSetup::$palmares_table, $condition, $parameters);
		
		$pagination = new ModulePagination($page, $number_palmares, (int)PalmaresConfig::load()->get_number_palmares_per_page());
		$pagination->set_url(PalmaresUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = PalmaresService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = PalmaresService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_descriptions_displayed_to_guests() && (!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || !$this->config->get_display_condensed_enabled())) || (!$this->config->are_descriptions_displayed_to_guests() && !PalmaresAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!PalmaresAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
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
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['palmares']);
		else
			$graphical_environment->set_page_title($this->lang['palmares']);
		
		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PalmaresUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
	
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['palmares'], PalmaresUrlBuilder::home());
		
		$categories = array_reverse(PalmaresService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PalmaresUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->tpl;
	}
}
?>
