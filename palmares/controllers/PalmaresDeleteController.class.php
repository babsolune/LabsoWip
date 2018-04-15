<?php
/*##################################################
 *		                  PalmaresDeleteController.class.php
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
class PalmaresDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$palmares = $this->get_palmares($request);
		
		if (!$palmares->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		
		PalmaresService::delete('WHERE id=:id', array('id' => $palmares->get_id()));
		PalmaresService::get_keywords_manager()->delete_relations($palmares->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'palmares', 'id' => $palmares->get_id()));
		
		CommentsService::delete_comments_topic_module('palmares', $palmares->get_id());
		
		Feed::clear_cache('palmares');
		PalmaresCategoriesCache::invalidate();
		
		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), PalmaresUrlBuilder::display_palmares($palmares->get_category()->get_id(), $palmares->get_category()->get_rewrited_name(), $palmares->get_id(), $palmares->get_rewrited_name())->rel()) ? $request->get_url_referrer() : PalmaresUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('palmares.message.success.delete', 'common', 'palmares'), array('name' => $palmares->get_name())));
	}
	
	private function get_palmares(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		
		if (!empty($id))
		{
			try {
				return PalmaresService::get_palmares('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>