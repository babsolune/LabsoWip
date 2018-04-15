<?php
/*##################################################
 *		       PortfolioDeleteItemController.class.php
 *                            -------------------
 *   begin                : November 29, 2017
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

class PortfolioDeleteItemController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$work = $this->get_work($request);

		if (!$work->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		PortfolioService::delete('WHERE id=:id', array('id' => $work->get_id()));
		PortfolioService::get_keywords_manager()->delete_relations($work->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'portfolio', 'id' => $work->get_id()));

		CommentsService::delete_comments_topic_module('portfolio', $work->get_id());
		NotationService::delete_notes_id_in_module('portfolio', $work->get_id());

		Feed::clear_cache('portfolio');
		PortfolioCategoriesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), PortfolioUrlBuilder::display_item($work->get_category()->get_id(), $work->get_category()->get_rewrited_name(), $work->get_id(), $work->get_rewrited_title())->rel()) ? $request->get_url_referrer() : PortfolioUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('portfolio.message.success.delete', 'common', 'portfolio'), array('title' => $work->get_title())));
	}

	private function get_work(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return PortfolioService::get_work('WHERE portfolio.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
