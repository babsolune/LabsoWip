<?php
/*##################################################
 *		       ModcatfullDeleteItemController.class.php
 *                            -------------------
 *   begin                : Month XX, 2017
 *   copyright            : (C) 2017 Firstname LASTNAME
 *   email                : nickname@phpboost.com
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
 * @author Firstname LASTNAME <nickname@phpboost.com>
 */

class ModcatfullDeleteItemController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$itemcatfull = $this->get_itemcatfull($request);

		if (!$itemcatfull->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		ModcatfullService::delete('WHERE id=:id', array('id' => $itemcatfull->get_id()));
		ModcatfullService::get_keywords_manager()->delete_relations($itemcatfull->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'modcatfull', 'id' => $itemcatfull->get_id()));

		CommentsService::delete_comments_topic_module('modcatfull', $itemcatfull->get_id());
		NotationService::delete_notes_id_in_module('modcatfull', $itemcatfull->get_id());

		Feed::clear_cache('modcatfull');
		ModcatfullCategoriesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), ModcatfullUrlBuilder::display_item($itemcatfull->get_category()->get_id(), $itemcatfull->get_category()->get_rewrited_name(), $itemcatfull->get_id(), $itemcatfull->get_rewrited_title())->rel()) ? $request->get_url_referrer() : ModcatfullUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('modcatfull.message.success.delete', 'common', 'modcatfull'), array('title' => $itemcatfull->get_title())));
	}

	private function get_itemcatfull(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return ModcatfullService::get_itemcatfull('WHERE modcatfull.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
