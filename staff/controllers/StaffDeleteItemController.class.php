<?php
/**
 *				StaffDeleteItemController.class.php
 *				------------------
 * @copyright 	2005-2019 PHPBoost
 * @license 	https://opensource.org/licenses/GPL-3.0
 *
 * @since 		PHPBoost 5.2 - 2017-06-29
 * @author 		Sebastien LARTIGUE - <babsolune@phpboost.com>
 *
 * @category 	module
 * @package 	staff
 * @subpackage	controllers
 * @desc 		Delete item
*/

class StaffDeleteItemController extends ModuleController
{
	private $adherent;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$this->get_adherent($request);

		$this->check_authorizations();

		StaffService::delete('WHERE id=:id', array('id' => $this->adherent->get_id()));
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'staff', 'id' => $this->adherent->get_id()));

		Feed::clear_cache('staff');
		StaffCategoriesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), StaffUrlBuilder::display($this->adherent->get_category()->get_id(), $this->adherent->get_category()->get_rewrited_name(), $this->adherent->get_id(), $this->adherent->get_rewrited_name())->rel()) ? $request->get_url_referrer() : StaffUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('staff.message.success.delete', 'common', 'staff'), array('firstname' => $this->adherent->get_firstname(), 'lastname' => $this->adherent->get_lastname())));
	}

	private function get_adherent(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->adherent = StaffService::get_adherent('WHERE staff.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function check_authorizations()
	{
		if (!$this->adherent->is_authorized_to_delete())
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
