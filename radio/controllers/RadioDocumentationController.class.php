radio<?php
/*##################################################
 *		                RadioDocumentationController.class.php
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
 class RadioDocumentationController  extends ModuleController
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
         $this->template = new FileTemplate('radio/RadioDocumentationController.tpl');
         $this->lang = LangLoader::get('common', 'radio');
         $this->template->add_lang($this->lang);
     }

     private function build_response(View $view)
     {
         $config = RadioConfig::load();
         $response = new SiteDisplayResponse($view);
         $graphical_environment = $response->get_graphical_environment();
         $graphical_environment->set_page_title($this->lang['radio.documentation.title']);
         $breadcrumb = $graphical_environment->get_breadcrumb();
         $breadcrumb->add($this->lang['radio'], RadioUrlBuilder::home());
         $breadcrumb->add($this->lang['radio.documentation.title']);
         $this->template->put_all(array(
             'DOCUMENTATION_TITLE' => $this->lang['radio.documentation.title'],
             'DOCUMENTATION_CONTENT' => FormatingHelper::second_parse($config->get_documentation())
         ));
         return $response;
     }
 }
?>
