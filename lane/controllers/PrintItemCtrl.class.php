<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

namespace Wiki\controllers;
use \Wiki\services\ModAuthorizations;
use \Wiki\services\ModItem;
use \Wiki\services\ModServices;

class PrintItemCtrl extends ModuleController
{
	private $lang;
	private $view;
	private $moditem;

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

	private function get_moditem()
	{
		if ($this->moditem === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->moditem = ModServices::get_moditem('WHERE item.id=:id', array('id' => $id));
				}
				catch (RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->moditem = new ModItem();
		}
		return $this->moditem;
	}

	private function build_view()
	{
		$contents = preg_replace('`\[page\](.*)\[/page\]`u', '<h2>$1</h2>', $this->moditem->get_contents());
		$this->view->put_all(array(
			'PAGE_TITLE' => $this->lang['print.item'] . ' - ' . $this->moditem->get_title() . ' - ' . GeneralConfig::load()->get_site_name(),
			'TITLE' => $this->moditem->get_title(),
			'CONTENT' => FormatingHelper::second_parse($contents)
		));
	}

	private function check_authorizations()
	{
		$moditem = $this->get_moditem();

		$not_authorized = !ModAuthorizations::check_authorizations($moditem->get_category_id())->write() && (!ModAuthorizations::check_authorizations($moditem->get_category_id())->moderation() && $moditem->get_author_user()->get_id() != AppContext::get_current_user()->get_id());

		switch ($moditem->get_publishing_state())
		{
			case ModItem::PUBLISHED_NOW:
				if (!ModAuthorizations::check_authorizations()->read() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case ModItem::NOT_PUBLISHED:
				if ($not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case ModItem::PUBLISHED_DATE:
				if (!$moditem->is_published() && $not_authorized)
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
