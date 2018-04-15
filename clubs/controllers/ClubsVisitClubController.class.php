<?php
/*##################################################
 *                          ClubsVisitClubController.class.php
 *                            -------------------
 *   begin                : June 23, 2017
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

class ClubsVisitClubController extends AbstractController
{
	private $club;

	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->club = ClubsService::get_club('WHERE clubs.id = :id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}

		if ($this->club !== null && !ClubsAuthorizationsService::check_authorizations($this->club->get_id_category())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if ($this->club !== null && $this->club->is_visible())
		{
			$this->club->set_number_views($this->club->get_number_views() + 1);
			ClubsService::update_number_views($this->club);
			ClubsCache::invalidate();

			AppContext::get_response()->redirect($this->club->get_website_url()->absolute());
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
