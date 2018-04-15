<?php
/*##################################################
 *		                         PalmaresDisplayPendingPalmaresController.class.php
 *                            -------------------
 *   begin                : February 13, 2013
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
class PalmaresDisplayPendingPalmaresController extends ModuleController
{
	private $tpl;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'palmares');
		$this->tpl = new FileTemplate('palmares/PalmaresDisplaySeveralPalmaresController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	public function build_view()
	{
		$now = new Date();
		$authorized_categories = PalmaresService::get_authorized_categories(Category::ROOT_CATEGORY);
		$palmares_config = PalmaresConfig::load();
		$comments_config = new PalmaresComments();
		
		$condition = 'WHERE id_category IN :authorized_categories
		' . (!PalmaresAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (approbation_type = 0 OR (approbation_type = 2 AND (start_date > :timestamp_now OR (end_date != 0 AND end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
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
		
		$number_columns_display_palmares = $palmares_config->get_number_columns_display_palmares();
		$this->tpl->put_all(array(
			'C_DISPLAY_BLOCK_TYPE' => $palmares_config->get_display_type() == PalmaresConfig::DISPLAY_BLOCK,
			'C_DISPLAY_LIST_TYPE' => $palmares_config->get_display_type() == PalmaresConfig::DISPLAY_LIST,
			'C_DISPLAY_CONDENSED_CONTENT' => $palmares_config->get_display_condensed_enabled(),
			'C_COMMENTS_ENABLED' => $comments_config->are_comments_enabled(),
			
			'C_PALMARES_NO_AVAILABLE' => $result->get_rows_count() == 0,
			'C_PENDING_PALMARES' => true,
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
		$pagination->set_url(PalmaresUrlBuilder::display_pending_palmares('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function check_authorizations()
	{
		if (!(PalmaresAuthorizationsService::check_authorizations()->write() || PalmaresAuthorizationsService::check_authorizations()->contribution() || PalmaresAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['palmares.pending'], $this->lang['palmares']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['palmares.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PalmaresUrlBuilder::display_pending_palmares(AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['palmares'], PalmaresUrlBuilder::home());
		$breadcrumb->add($this->lang['palmares.pending'], PalmaresUrlBuilder::display_pending_palmares());
		
		return $response;
	}
}
?>