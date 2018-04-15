<?php
/*##################################################
 *		                         RadioDisplayPendingRadioController.class.php
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

class RadioDisplayPendingRadioController extends ModuleController
{
	private $tpl;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'radio');
		$this->tpl = new FileTemplate('radio/RadioDisplaySeveralProgramsController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	public function build_view()
	{
		$now = new Date();
		$authorized_categories = RadioService::get_authorized_categories(Category::ROOT_CATEGORY);
		$radio_config = RadioConfig::load();

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!RadioAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND approbation_type = 0';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT radio.*, member.*
		FROM '. RadioSetup::$radio_table .' radio
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = radio.author_user_id
		' . $condition . '
		ORDER BY radio.release_day ASC, radio.start_date ASC', array_merge($parameters, array(

		)));

		$this->tpl->put_all(array(
			'C_RADIO_NO_AVAILABLE' => $result->get_rows_count() == 0,
			'C_PENDING_RADIO' => true,
		));

		while ($row = $result->fetch())
		{
			$radio = new Radio();
			$radio->set_properties($row);

			$this->tpl->assign_block_vars('radio', $radio->get_array_tpl_vars());
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		if (!(RadioAuthorizationsService::check_authorizations()->write() || RadioAuthorizationsService::check_authorizations()->contribution() || RadioAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['radio.pending'], $this->lang['radio']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['radio.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(RadioUrlBuilder::display_pending_radio(AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['radio'], RadioUrlBuilder::home());
		$breadcrumb->add($this->lang['radio.pending'], RadioUrlBuilder::display_pending_radio());

		return $response;
	}
}
?>
