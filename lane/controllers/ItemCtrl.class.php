<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

namespace Wiki\controllers;
use \Wiki\phpboost\ModConfig;
use \Wiki\services\ModItem;
use \Wiki\services\ModServices;
use \Wiki\services\ModAuthorizations;
use \Wiki\util\ModUrlBuilder;

class ItemCtrl extends ModuleController
{
	private $lang;
	private $view;
	private $moditem;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->check_pending_moditem($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->view = new FileTemplate('wiki/ItemCtrl.tpl');
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
				$this->moditem = new Document();
		}
		return $this->moditem;
	}

	private function check_pending_moditem(HTTPRequestCustom $request)
	{
		if (!$this->moditem->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), ModUrlBuilder::display_item($this->moditem->get_category()->get_id(), $this->moditem->get_category()->get_rewrited_name(), $this->moditem->get_id(), $this->moditem->get_rewrited_title())->rel()))
			{
				$this->moditem->set_number_view($this->moditem->get_number_view() + 1);
				ModServices::update_number_view($this->moditem);
			}
		}
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$current_page = $request->get_getint('page', 1);
		$config = ModConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$this->category = $this->moditem->get_category();

		$moditem_contents = $this->moditem->get_contents();

		//If the article doesn't begin with a page, we insert one
		if (TextHelper::substr(trim($moditem_contents), 0, 6) != '[page]')
		{
			$moditem_contents = '[page]&nbsp;[/page]' . $moditem_contents;
		}

		//Removing [page] bbcode
		$moditem_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $moditem_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		//Retrieving pages
		preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $moditem_contents, $array_page);

		$nbr_pages = count($array_page[1]);

		if ($nbr_pages > 1)
			$this->build_pages_menu($array_page, $current_page);

		$keywords = $this->moditem->get_keywords();
		$has_keywords = count($keywords) > 0;

		if ($has_keywords)
		$this->build_keywords_view();

		$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

		$this->view->put_all(array_merge($this->moditem->get_array_tpl_vars(), array(
			'C_KEYWORDS' => $has_keywords,
			'CONTENTS'           => isset($moditem_contents_clean[$current_page-1]) ? FormatingHelper::second_parse($moditem_contents_clean[$current_page-1]) : '',
			'PAGE_NAME'          => $page_name,
			'U_EDIT_ITEM'     => $page_name !== '' ? ModUrlBuilder::edit_item($this->moditem->get_id(), $current_page)->rel() : ModUrlBuilder::edit_item($this->moditem->get_id())->rel()
		)));

		$this->build_pages_pagination($current_page, $nbr_pages, $array_page);
	}

	private function build_pages_menu($array_page, $current_page)
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('page-menu');

		$fieldset = new FormFieldsetHorizontal('pages', array('description' => $this->lang['pages.list']));

		$form->add_fieldset($fieldset);

		$item_pages = $this->pages_menu_list($array_page);

		$fieldset->add_field(new FormFieldActionLinkList('item_pages', $item_pages, $current_page));

		$this->view->put('PAGES_MENU', $form->display());
	}

	private function pages_menu_list($array_page)
	{
		$options = array();

		$i = 1;
		foreach ($array_page[1] as $page_name)
		{
			$page_link = $this->category->get_id().'-'.$this->category->get_rewrited_name().'/'.$this->moditem->get_id().'-'.$this->moditem->get_rewrited_title() . '/' . $i;
			$options[] = new FormFieldActionLinkElement($page_name, $page_link, '');
			$i++;
		}

		return $options;
	}

	private function build_pages_pagination($current_page, $nbr_pages, $array_page)
	{
		if ($nbr_pages > 1)
		{
			$pagination = $this->get_pagination($nbr_pages, $current_page);

			if ($current_page > 1 && $current_page <= $nbr_pages)
			{
				$previous_page = ModUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->moditem->get_id(), $this->moditem->get_rewrited_title())->rel() . ($current_page - 1);

				$this->view->put_all(array(
					'U_PREVIOUS_PAGE' => $previous_page,
					'L_PREVIOUS_TITLE' => $array_page[1][$current_page-2]
				));
			}

			if ($current_page > 0 && $current_page < $nbr_pages)
			{
				$next_page = ModUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->moditem->get_id(), $this->moditem->get_rewrited_title())->rel() . ($current_page + 1);

				$this->view->put_all(array(
					'U_NEXT_PAGE' => $next_page,
					'L_NEXT_TITLE' => $array_page[1][$current_page]
				));
			}

			$this->view->put_all(array(
				'C_PAGINATION' => true,
				'C_PREVIOUS_PAGE' => ($current_page != 1) ? true : false,
				'C_NEXT_PAGE' => ($current_page != $nbr_pages) ? true : false,
				'PAGINATION_ITEMS' => $pagination->display()
			));
		}
	}

	private function build_keywords_view()
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => ModUrlBuilder::display_keyword($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$moditem = $this->get_moditem();

		$current_user = AppContext::get_current_user();
		$not_authorized = !ModAuthorizations::check_authorizations($moditem->get_category_id())->moderation() && !ModAuthorizations::check_authorizations($moditem->get_category_id())->write() && (!ModAuthorizations::check_authorizations($moditem->get_category_id())->contribution() || $moditem->get_author_user()->get_id() != $current_user->get_id());

		switch ($moditem->get_publishing_state())
		{
			case ModItem::PUBLISHED_NOW:
				if (!ModAuthorizations::check_authorizations($moditem->get_category_id())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case ModItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case ModItem::PUBLISHED_DATE:
				if (!$moditem->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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

	private function get_pagination($nbr_pages, $current_page)
	{
		$pagination = new ModulePagination($current_page, $nbr_pages, 1, Pagination::LIGHT_PAGINATION);
		$pagination->set_url(ModUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->moditem->get_id(), $this->moditem->get_rewrited_title(), '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->moditem->get_title(), $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->moditem->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ModUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->moditem->get_id(), $this->moditem->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], ModUrlBuilder::home());

		$categories = array_reverse(ModServices::get_categories_manager()->get_parents($this->moditem->get_category_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), ModUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->moditem->get_title(), ModUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->moditem->get_id(), $this->moditem->get_rewrited_title()));

		return $response;
	}
}
?>
