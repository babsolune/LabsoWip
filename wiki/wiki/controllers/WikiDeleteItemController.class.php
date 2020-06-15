<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

class WikiDeleteItemController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$document = $this->get_document($request);

		if (!$document->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		WikiService::delete('WHERE id=:id', array('id' => $document->get_id()));
		WikiService::get_keywords_manager()->delete_relations($document->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'wiki', 'id' => $document->get_id()));

		Feed::clear_cache('wiki');
		WikiCategoriesCache::invalidate();
		WikiKeywordsCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), WikiUrlBuilder::display_item($document->get_category()->get_id(), $document->get_category()->get_rewrited_name(), $document->get_id(), $document->get_rewrited_title())->rel()) ? $request->get_url_referrer() : WikiUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('wiki.message.success.delete', 'common', 'wiki'), array('title' => $document->get_title())));
	}

	private function get_document(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return WikiService::get_document('WHERE wiki.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
