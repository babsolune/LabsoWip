<?php
/*##################################################
 *                               PartnerDeletePartnerController.class.php
 *                            -------------------
 *   begin                : September 13, 2017
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class PartnerDeletePartnerController extends ModuleController
{
	private $partner;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$this->get_partner($request);

		$this->check_authorizations();

		SponsorsService::delete('WHERE id=:id', array('id' => $this->partner->get_id()));
		SponsorsService::get_keywords_manager()->delete_relations($this->partner->get_id());
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'sponsors', 'id' => $this->partner->get_id()));

		SponsorsCache::invalidate();
		SponsorsCategoriesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), SponsorsUrlBuilder::display($this->partner->get_category()->get_id(), $this->partner->get_category()->get_rewrited_name(), $this->partner->get_id(), $this->partner->get_rewrited_name())->rel()) ? $request->get_url_referrer() : SponsorsUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('sponsors.message.success.delete', 'common', 'sponsors'), array('name' => $this->partner->get_name())));
	}

	private function get_partner(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->partner = SponsorsService::get_partner('WHERE sponsors.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function check_authorizations()
	{
		if (!$this->partner->is_authorized_to_delete())
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
