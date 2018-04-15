<?php
/*##################################################
 *                               CatalogDisplayProductController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class CatalogDisplayProductController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $product;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->count_number_view($request);
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'catalog');
		$this->tpl = new FileTemplate('catalog/CatalogDisplayProductController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function get_product()
	{
		if ($this->product === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->product = CatalogService::get_product('WHERE catalog.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->product = new Product();
		}
		return $this->product;
	}
	
	private function count_number_view(HTTPRequestCustom $request)
	{
		if (!$this->product->is_visible())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), CatalogUrlBuilder::display($this->product->get_category()->get_id(), $this->product->get_category()->get_rewrited_name(), $this->product->get_id(), $this->product->get_rewrited_name())->rel()))
			{
				$this->product->set_number_view($this->product->get_number_view() + 1);
				CatalogService::update_number_view($this->product);
			}
		}
	}
	
	private function build_view()
	{
		$config = CatalogConfig::load();
		$comments_config = new CatalogComments();
		$notation_config = new CatalogNotation();
		$product = $this->get_product();
		$category = $product->get_category();
		
		$keywords = $product->get_keywords();
		$has_keywords = count($keywords) > 0;
		
		$this->tpl->put_all(array_merge($product->get_array_tpl_vars(), array(
			'C_AUTHOR_DISPLAYED' => $config->is_author_displayed(),
			'C_COMMENTS_ENABLED' => $comments_config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $notation_config->is_notation_enabled(),
			'C_KEYWORDS' => $has_keywords,
			'C_DISPLAY_PRODUCT' => CatalogAuthorizationsService::check_authorizations()->display_product(),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING),
			'UNAUTHORIZED_TO_DOWNLOAD_MESSAGE' => MessageHelper::display($this->lang['catalog.message.warning.unauthorized_to_catalog_product'], MessageHelper::WARNING)
		)));
		
		if ($comments_config->are_comments_enabled())
		{
			$comments_topic = new CatalogCommentsTopic($product);
			$comments_topic->set_id_in_module($product->get_id());
			$comments_topic->set_url(CatalogUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $product->get_id(), $product->get_rewrited_name()));
			
			$this->tpl->put('COMMENTS', $comments_topic->display());
		}
		
		if ($has_keywords)
			$this->build_keywords_view($keywords);
	}
	
	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);
		
		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => CatalogUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}
	
	private function check_authorizations()
	{
		$product = $this->get_product();
		
		$current_user = AppContext::get_current_user();
		$not_authorized = !CatalogAuthorizationsService::check_authorizations($product->get_id_category())->moderation() && !CatalogAuthorizationsService::check_authorizations($product->get_id_category())->write() && (!CatalogAuthorizationsService::check_authorizations($product->get_id_category())->contribution() || $product->get_author_user()->get_id() != $current_user->get_id());
		
		switch ($product->get_approbation_type()) {
			case Product::APPROVAL_NOW:
				if (!CatalogAuthorizationsService::check_authorizations($product->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Product::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case Product::APPROVAL_DATE:
				if (!$product->is_visible() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
	
	private function generate_response()
	{
		$product = $this->get_product();
		$category = $product->get_category();
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($product->get_name(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($product->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CatalogUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $product->get_id(), $product->get_rewrited_name()));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'],CatalogUrlBuilder::home());
		
		$categories = array_reverse(CatalogService::get_categories_manager()->get_parents($product->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), CatalogUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($product->get_name(), CatalogUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $product->get_id(), $product->get_rewrited_name()));
		
		return $response;
	}
}
?>
