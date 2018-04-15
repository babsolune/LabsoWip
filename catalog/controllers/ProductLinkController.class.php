<?php
/*##################################################
 *                          ProductLinkController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class ProductLinkController extends AbstractController
{
	private $product;

	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->product = CatalogService::get_product('WHERE catalog.id = :id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}

		if ($this->product !== null && $this->product->is_downloadable() && (!CatalogAuthorizationsService::check_authorizations($this->product->get_id_category())->read() || !CatalogAuthorizationsService::check_authorizations()->display_product()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if ($this->product !== null && $this->product->is_downloadable() && $this->product->is_visible())
		{
			$this->product->set_number_downloads($this->product->set_number_downloads() + 1);
			CatalogService::update_number_downloads($this->product);
			CatalogCache::invalidate();

			if (Url::check_url_validity($this->product->get_url()->absolute()) || Url::check_url_validity($this->product->get_url()->relative()))
			{
				header('Content-Description: File Transfer');
				header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');
				header('Content-Type: application/force-catalog');
				header('Location: ' . $this->product->get_url()->absolute());

				return $this->generate_response();
			}
			else
			{
				$error_controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('catalog.message.error.product_not_found', 'common', 'catalog'), UserErrorController::WARNING);
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse(new StringTemplate(''));

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->product->get_name(), LangLoader::get_message('module_title', 'common', 'catalog'));
		$graphical_environment->get_seo_meta_data()->set_description($this->product->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CatalogUrlBuilder::download_product_link($this->product->get_id()));

		return $response;
	}
}
?>
