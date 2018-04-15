<?php
/*##################################################
 *                               ClubsDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : June 23, 2017
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class ClubsDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;
	private $comments_config;
	private $notation_config;

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
		$this->lang = LangLoader::get('common', 'clubs');
		$this->tpl = new FileTemplate('clubs/ClubsDisplaySeveralClubsController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = ClubsConfig::load();
		$this->comments_config = new ClubsComments();
		$this->notation_config = new ClubsNotation();

	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$page = AppContext::get_request()->get_getint('page', 1);
		$subcategories_page = AppContext::get_request()->get_getint('subcategories_page', 1);

		$subcategories = ClubsService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), ClubsService::get_authorized_categories($this->get_category()->get_id()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_number_per_page(), $page, $subcategories_page);


		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_image = $category->get_image()->rel();

				$this->tpl->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_IMAGE' => !empty($category_image),
					'C_MORE_THAN_ONE_CLUB' => $category->get_elements_number() > 1,
					'CATEGORY_ID' => $category->get_id(),
					'CATEGORY_NAME' => $category->get_name(),
					'CATEGORY_IMAGE' => $category_image,
					'CLUBS_NUMBER' => $category->get_elements_number(),
					'U_CATEGORY' => ClubsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$nbr_column_cats_per_line = ($nbr_cat_displayed > $this->config->get_columns_number_per_line()) ? $this->config->get_columns_number_per_line() : $nbr_cat_displayed;
		$nbr_column_cats_per_line = !empty($nbr_column_cats_per_line) ? $nbr_column_cats_per_line : 1;

		$condition = 'WHERE id_category = :id_category
		AND approbation_type = 1';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $page, $subcategories_page);

		$result = PersistenceContext::get_querier()->select('SELECT clubs.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM '. ClubsSetup::$clubs_table .' clubs
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = clubs.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = clubs.id AND com.module_id = \'clubs\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = clubs.id AND notes.module_name = \'clubs\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = clubs.id AND note.module_name = \'clubs\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY clubs.name ASC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

        if($this->config->is_gmap_api())
        {
            $googlemaps = GoogleMapsConfig::load();
            $gmap_api_key = $googlemaps->get_api_key();
        } else
            $gmap_api_key = '';

		$this->tpl->put_all(array(
            'GMAP_API_KEY' => $gmap_api_key,
            'C_CAT_GMAP_ENABLED' => $this->config->is_gmap_api(),
            'C_DEFAULT_ADDRESS' => !empty($this->config->get_default_latitude()) && !empty($this->config->get_default_longitude()),
            'DEFAULT_LAT' => $this->config->get_default_latitude(),
            'DEFAULT_LNG' => $this->config->get_default_longitude(),
			'C_CLUBS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_CLUB' => $result->get_rows_count() > 1,
			'C_CATEGORY_DISPLAYED_TABLE' => $this->config->is_category_displayed_table(),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_COMMENTS_ENABLED' => $this->comments_config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $this->notation_config->is_notation_enabled(),
			'C_MODERATE' => ClubsAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'C_SEVERAL_CATS_COLUMNS' => $nbr_column_cats_per_line > 1,
			'NUMBER_CATS_COLUMNS' => $nbr_column_cats_per_line,
			'PAGINATION' => $pagination->display(),
			'TABLE_COLSPAN' => 3 + (int)$this->comments_config->are_comments_enabled() + (int)$this->notation_config->is_notation_enabled(),
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? ClubsUrlBuilder::configuration()->rel() : ClubsUrlBuilder::edit_category($this->get_category()->get_id())->rel()
		));

		while ($row = $result->fetch())
		{
			$club = new Club();
			$club->set_properties($row);

			$this->tpl->assign_block_vars('clubs', array_merge($club->get_array_tpl_vars()));

		}
		$result->dispose();
	}



	private function get_pagination($condition, $parameters, $page, $subcategories_page)
	{
		$clubs_number = ClubsService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $clubs_number, (int)ClubsConfig::load()->get_items_number_per_page());
		$pagination->set_url(ClubsUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), '%d', $subcategories_page));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_subcategories_pagination($subcategories_number, $categories_number_per_page, $page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$categories_number_per_page);
		$pagination->set_url(ClubsUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $page, '%d'));

		if ($pagination->current_page_is_empty() && $subcategories_page > 1)
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
					$this->category = ClubsService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = ClubsService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || $this->config->get_category_display_type() == ClubsConfig::DISPLAY_ALL_CONTENT || !ClubsAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!ClubsAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
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
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title']);
		else
			$graphical_environment->set_page_title($this->lang['module_title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ClubsUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], ClubsUrlBuilder::home());

		$categories = array_reverse(ClubsService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), ClubsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

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
