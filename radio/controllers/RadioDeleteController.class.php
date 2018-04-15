<?php
/*##################################################
 *		                  RadioDeleteController.class.php
 *                            -------------------
 *   begin                : May, 02, 2017
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

class RadioDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$radio = $this->get_radio($request);

		if (!$radio->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		RadioService::delete('WHERE id=:id', array('id' => $radio->get_id()));

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'radio', 'id' => $radio->get_id()));
		
		RadioCategoriesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), RadioUrlBuilder::display_radio($radio->get_category()->get_id(), $radio->get_category()->get_rewrited_name(), $radio->get_id(), $radio->get_rewrited_name())->rel()) ? $request->get_url_referrer() : RadioUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('radio.message.success.delete', 'common', 'radio'), array('name' => $radio->get_name())));
	}

	private function get_radio(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				return RadioService::get_radio('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
