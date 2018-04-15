<?php
/*##################################################
 *                               ClubsDisplayClubController.class.php
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

class ClubsDisplayClubController extends ModuleController
{
	private $lang;
	private $tpl;
    private $config;

	private $club;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'clubs');
		$this->tpl = new FileTemplate('clubs/ClubsDisplayClubController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = ClubsConfig::load();
	}

	private function get_club()
	{
		if ($this->club === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->club = ClubsService::get_club('WHERE clubs.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->club = new Club();
		}
		return $this->club;
	}

	private function build_view()
	{
		$comments_config = new ClubsComments();
		$notation_config = new ClubsNotation();
		$club = $this->get_club();
		$category = $club->get_category();

		$this->build_location_view();
		$this->build_colors_view();

		$this->tpl->put_all(array_merge($club->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $comments_config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $notation_config->is_notation_enabled(),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		if ($comments_config->are_comments_enabled())
		{
			$comments_topic = new ClubsCommentsTopic($club);
			$comments_topic->set_id_in_module($club->get_id());
			$comments_topic->set_url(ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $club->get_id(), $club->get_rewrited_name()));

			$this->tpl->put('COMMENTS', $comments_topic->display());
		}
	}

	private function check_authorizations()
	{
		$club = $this->get_club();

		$current_user = AppContext::get_current_user();
		$not_authorized = !ClubsAuthorizationsService::check_authorizations($club->get_id_category())->moderation() && !ClubsAuthorizationsService::check_authorizations($club->get_id_category())->write() && (!ClubsAuthorizationsService::check_authorizations($club->get_id_category())->contribution() || $club->get_author_user()->get_id() != $current_user->get_id());

		switch ($club->get_approbation_type()) {
			case Club::APPROVAL_NOW:
				if (!ClubsAuthorizationsService::check_authorizations($club->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Club::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
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

	private function build_location_view()
	{
        // $unserialized_value = @unserialize($this->club->get_location());
		// $location_value = $unserialized_value !== false ? $unserialized_value : $this->club->get_location();
		// $location = '';
        //
		// $this->tpl->assign_block_vars('location', array(
        //     'ADDRESS'=> $location_value['address'],
        //     'C_STREET_NUMBER' => !empty($location_value['street_number']),
		// 	'STREET_NUMBER' => $location_value['street_number'],
		// 	'ROUTE' => $location_value['route'],
		// 	'CITY' => $location_value['city'],
		// 	'POSTAL_CODE' => $location_value['postal_code'],
		// ));
        $location = $this->club->get_location();

		foreach ($location as $name => $options)
		{
			$this->tpl->assign_block_vars('location', array(
                'C_STREET_NUMBER' => !empty($options['street_number']),
    			'STREET_NUMBER' => $options['street_number'],
    			'ROUTE' => $options['route'],
    			'CITY' => $options['city'],
    			'POSTAL_CODE' => $options['postal_code'],
			));
		}
	}

	private function build_colors_view()
	{
		$colors = $this->club->get_colors();
		$nbr_colors = count($colors);
        $this->tpl->put('C_COLORS', $nbr_colors > 0);

		$i = 1;
		foreach ($colors as $name => $color)
		{
			$this->tpl->assign_block_vars('colors', array(
				'C_SEPARATOR' => $i < $nbr_colors,
				'NAME' => $name,
				'COLOR' => $color,
			));
			$i++;
		}
	}

	private function generate_response()
	{
		$club = $this->get_club();
		$category = $club->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($club->get_name(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $club->get_id(), $club->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'],ClubsUrlBuilder::home());

		$categories = array_reverse(ClubsService::get_categories_manager()->get_parents($club->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), ClubsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($club->get_name(), ClubsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $club->get_id(), $club->get_rewrited_name()));

		return $response;
	}
}
?>
