<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

class WikiDisplayItemController extends ModuleController
{
	private $lang;
	private $tpl;
	private $document;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->check_pending_document($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'wiki');
		$this->tpl = new FileTemplate('wiki/WikiDisplayItemController.tpl');
		$this->tpl->add_lang($this->lang);
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

	private function check_pending_document(HTTPRequestCustom $request)
	{
		if (!$this->document->is_published())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), WikiUrlBuilder::display_item($this->document->get_category()->get_id(), $this->document->get_category()->get_rewrited_name(), $this->document->get_id(), $this->document->get_rewrited_title())->rel()))
			{
				$this->document->set_number_view($this->document->get_number_view() + 1);
				WikiService::update_number_view($this->document);
			}
		}
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$current_page = $request->get_getint('page', 1);
		$config = WikiConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$this->category = $this->document->get_category();

		$document_contents = $this->document->get_contents();

		//If document doesn't begin with a page, we insert one
		if (TextHelper::substr(trim($document_contents), 0, 6) != '[page]')
		{
			$document_contents = '[page]&nbsp;[/page]' . $document_contents;
		}

		//Removing [page] bbcode
		$document_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $document_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		//Retrieving pages
		preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $document_contents, $array_page);

		$nbr_pages = count($array_page[1]);

		if ($nbr_pages > 1)
			$this->build_pages_menu($array_page, $current_page);

		$keywords = $this->document->get_keywords();
		$has_keywords = count($keywords) > 0;

		if ($has_keywords)
		$this->build_keywords_view();

		$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

		$this->tpl->put_all(array_merge($this->document->get_array_tpl_vars(), array(
			'C_KEYWORDS' => $has_keywords,
			'CONTENTS'           => isset($document_contents_clean[$current_page-1]) ? FormatingHelper::second_parse($document_contents_clean[$current_page-1]) : '',
			'PAGE_NAME'          => $page_name,
			'U_EDIT_DOCUMENT'     => $page_name !== '' ? WikiUrlBuilder::edit_item($this->document->get_id(), $current_page)->rel() : WikiUrlBuilder::edit_item($this->document->get_id())->rel()
		)));

		$this->build_pages_pagination($current_page, $nbr_pages, $array_page);
	}

	private function build_pages_menu($array_page, $current_page)
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('pages-menu');

		$fieldset = new FormFieldsetHorizontal('pages', array('description' => $this->lang['wiki.document.pages']));

		$form->add_fieldset($fieldset);

		$document_pages = $this->list_document_pages($array_page);

		$fieldset->add_field(new FormFieldActionLinkList('document_pages', $document_pages, $current_page),
			array('class' => 'plop')
		);

		$this->tpl->put('PAGES_MENU', $form->display());
	}

	private function list_document_pages($array_page)
	{
		$options = array();


		$i = 1;
		foreach ($array_page[1] as $page_name)
		{
			$page_link = $this->category->get_id().'-'.$this->category->get_rewrited_name().'/'.$this->document->get_id().'-'.$this->document->get_rewrited_title() . '/' . $i;
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
				$previous_page = WikiUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->document->get_id(), $this->document->get_rewrited_title())->rel() . ($current_page - 1);

				$this->tpl->put_all(array(
					'U_PREVIOUS_PAGE' => $previous_page,
					'L_PREVIOUS_TITLE' => $array_page[1][$current_page-2]
				));
			}

			if ($current_page > 0 && $current_page < $nbr_pages)
			{
				$next_page = WikiUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->document->get_id(), $this->document->get_rewrited_title())->rel() . ($current_page + 1);

				$this->tpl->put_all(array(
					'U_NEXT_PAGE' => $next_page,
					'L_NEXT_TITLE' => $array_page[1][$current_page]
				));
			}

			$this->tpl->put_all(array(
				'C_PAGINATION' => true,
				'C_PREVIOUS_PAGE' => ($current_page != 1) ? true : false,
				'C_NEXT_PAGE' => ($current_page != $nbr_pages) ? true : false,
				'PAGINATION_DOCUMENTS' => $pagination->display()
			));
		}
	}

	private function build_keywords_view()
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WikiUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$document = $this->get_document();

		$current_user = AppContext::get_current_user();
		$not_authorized = !WikiAuthorizationsService::check_authorizations($document->get_id_category())->moderation() && !WikiAuthorizationsService::check_authorizations($document->get_id_category())->write() && (!WikiAuthorizationsService::check_authorizations($document->get_id_category())->contribution() || $document->get_author_user()->get_id() != $current_user->get_id());

		switch ($document->get_publishing_state())
		{
			case Document::PUBLISHED_NOW:
				if (!WikiAuthorizationsService::check_authorizations($document->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Document::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Document::PUBLISHED_DATE:
				if (!$document->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$pagination->set_url(WikiUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->document->get_id(), $this->document->get_rewrited_title(), '%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->document->get_title(), $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->document->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::display_item($this->category->get_id(), $this->category->get_rewrited_name(), $this->document->get_id(), $this->document->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], WikiUrlBuilder::home());

		$categories = array_reverse(WikiService::get_categories_manager()->get_parents($this->document->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->document->get_title(), WikiUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->document->get_id(), $this->document->get_rewrited_title()));

		return $response;
	}
}
?>
