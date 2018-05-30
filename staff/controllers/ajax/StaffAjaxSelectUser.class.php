<?php
/*##################################################
 *		      StaffAjaxSelectUser.class.php
 *                            -------------------
 *   begin                : March 15, 2018
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

class StaffAjaxSelectUser extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$suggestions = array();

		try {
			$result = PersistenceContext::get_querier()->select("SELECT *
				FROM " . DB_TABLE_MEMBER . " WHERE display_name LIKE '" . str_replace('*', '%', $request->get_value('value', '')) . "%'
				LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ex ON ex.user_id = user_id
			");

			while($row = $result->fetch())
			{
				$profile_link = new LinkHTMLElement('', $row['display_name'], array('onclick' => 'return false;'));

				$suggestions[] = $profile_link->display();
			}
			$result->dispose();
		} catch (Exception $e) {
		}

		return new JSONResponse(array('suggestions' => $suggestions));
	}
}
?>
