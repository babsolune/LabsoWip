<?php
/*##################################################
 *		       WikiPrintItemController.class.php
 *                            -------------------
 *   begin                : May 25, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
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

class WikiPrintItemController extends ModuleController
{
	private $lang;
	private $view;
	private $document;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return new SiteNodisplayResponse($this->view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new FileTemplate('framework/content/print.tpl');
		$this->view->add_lang($this->lang);
	}

	private function get_document()
	{
		if ($this->document === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->document = WikiService::get_document('WHERE wiki.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->document = new Document();
		}
		return $this->document;
	}

	private function build_view()
	{
		$contents = preg_replace('`\[page\](.*)\[/page\]`u', '<h2>$1</h2>', $this->document->get_contents());
		$this->view->put_all(array(
			'PAGE_TITLE' => $this->lang['wiki.print.document'] . ' - ' . $this->document->get_title() . ' - ' . GeneralConfig::load()->get_site_name(),
			'TITLE' => $this->document->get_title(),
			'CONTENT' => FormatingHelper::second_parse($contents)
		));
	}

	private function check_authorizations()
	{
		$document = $this->get_document();

		$not_authorized = !WikiAuthorizationsService::check_authorizations($document->get_id_category())->write() && (!WikiAuthorizationsService::check_authorizations($document->get_id_category())->moderation() && $document->get_author_user()->get_id() != AppContext::get_current_user()->get_id());

		switch ($document->get_publishing_state())
		{
			case Document::PUBLISHED_NOW:
				if (!WikiAuthorizationsService::check_authorizations()->read() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Document::NOT_PUBLISHED:
				if ($not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Document::PUBLISHED_DATE:
				if (!$document->is_published() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}
}
?>
