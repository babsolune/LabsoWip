<?php
/*##################################################
 *                               StaffDeleteController.class.php
 *                            -------------------
 *   begin                : June 29, 2017
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
 * @author Seabstien LARTIGUE <babsolune@phpboost.com>
 */

class StaffDeleteController extends ModuleController
{
	private $member;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$this->get_member($request);

		$this->check_authorizations();

		StaffService::delete('WHERE id=:id', array('id' => $this->member->get_id()));
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'staff', 'id' => $this->member->get_id()));

		Feed::clear_cache('staff');
		StaffCategoriesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), StaffUrlBuilder::display($this->member->get_category()->get_id(), $this->member->get_category()->get_rewrited_name(), $this->member->get_id(), $this->member->get_rewrited_name())->rel()) ? $request->get_url_referrer() : StaffUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('staff.message.success.delete', 'common', 'staff'), array('firstname' => $this->member->get_firstname(), 'lastname' => $this->member->get_lastname())));
	}

	private function get_member(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->member = StaffService::get_member('WHERE staff.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function check_authorizations()
	{
		if (!$this->member->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
