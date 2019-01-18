<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

namespace Wiki\controllers;
use \Wiki\services\ModCategoriesCache;
use \Wiki\services\ModKeywordsCache;
use \Wiki\services\ModServices;
use \Wiki\util\ModUrlBuilder;

class ItemRemovalCtrl extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$moditem = $this->get_moditem($request);

		if (!$moditem->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		ModServices::delete('WHERE id=:id', array('id' => $moditem->get_id()));
		ModServices::get_keywords_manager()->delete_relations($moditem->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'wiki', 'id' => $moditem->get_id()));

		Feed::clear_cache('wiki');
		ModCategoriesCache::invalidate();
		ModKeywordsCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), ModUrlBuilder::display_item($moditem->get_category()->get_id(), $moditem->get_category()->get_rewrited_name(), $moditem->get_id(), $moditem->get_rewrited_title())->rel()) ? $request->get_url_referrer() : ModUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('delete.success.message.helper', 'common', 'wiki'), array('title' => $moditem->get_title())));
	}

	private function get_moditem(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return Service::get_moditem('WHERE item.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
