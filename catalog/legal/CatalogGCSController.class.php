<?php
/*##################################################
 *                         CatalogGCSController.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien LARTIGUE
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
 * @author Sebastien Lartigue <babsolune@phpboost.com>
 */


class CatalogGCSController  extends ModuleController
{
    private $lang;
    private $template;

    public function execute(HTTPRequestCustom $request)
    {

        $this->init();

        return $this->build_response($this->template);
    }

    private function init()
    {
        $this->template = new FileTemplate('catalog/legal/CatalogGCSController.tpl');
        $this->lang = LangLoader::get('gcs', 'catalog');
        $this->template->add_lang($this->lang);
    }

    private function build_response(View $view)
    {
        $config = CatalogConfig::load();
        $response = new SiteDisplayResponse($view);
	    $graphical_environment = $response->get_graphical_environment();
	    $graphical_environment->set_page_title($this->lang['catalog.gcs.title']);
	    $breadcrumb = $graphical_environment->get_breadcrumb();
	    $breadcrumb->add(Langloader::get_message('module_title', 'common', 'catalog'), CatalogUrlBuilder::home());
	    $breadcrumb->add($this->lang['catalog.gcs.title']);
        $this->template->put_all(array(
            'GCS_TITLE' => Langloader::get_message('catalog.gcs.title', 'gcs', 'catalog'),
            'GCS_CONTENT' => FormatingHelper::second_parse($config->get_gcs_text())
        ));
        return $response;
    }
}
