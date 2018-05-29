<?php
/*##################################################
 *		       PbtdocPrintItemController.class.php
 *                            -------------------
 *   begin                : June 03, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class PbtdocPrintItemController extends ModuleController
{
	private $lang;
	private $view;
	private $course;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return new SiteNodisplayResponse($this->view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'pbtdoc');
		$this->view = new FileTemplate('framework/content/print.tpl');
		$this->view->add_lang($this->lang);
	}

	private function get_course()
	{
		if ($this->course === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->course = PbtdocService::get_course('WHERE pbtdoc.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->course = new Course();
		}
		return $this->course;
	}

	private function build_view()
	{
		$contents = preg_replace('`\[page\](.*)\[/page\]`u', '<h2>$1</h2>', $this->course->get_contents());
		$this->view->put_all(array(
			'PAGE_TITLE' => $this->lang['pbtdoc.print.course'] . ' - ' . $this->course->get_title() . ' - ' . GeneralConfig::load()->get_site_name(),
			'TITLE' => $this->course->get_title(),
			'CONTENT' => FormatingHelper::second_parse($contents)
		));
	}

	private function check_authorizations()
	{
		$course = $this->get_course();

		$not_authorized = !PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->write() && (!PbtdocAuthorizationsService::check_authorizations($course->get_id_category())->moderation() && $course->get_author_user()->get_id() != AppContext::get_current_user()->get_id());

		switch ($course->get_publishing_state())
		{
			case Course::PUBLISHED_NOW:
				if (!PbtdocAuthorizationsService::check_authorizations()->read() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Course::NOT_PUBLISHED:
				if ($not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Course::PUBLISHED_DATE:
				if (!$course->is_published() && $not_authorized)
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
