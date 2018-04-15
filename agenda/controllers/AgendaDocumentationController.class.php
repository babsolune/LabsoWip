<?php
/*##################################################
 *		             AgendaDocumentationController.class.php
 *                            -------------------
 *   begin                : May 22, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class AgendaDocumentationController  extends ModuleController
{
   private $lang;
   private $template;

   public function execute(HTTPRequestCustom $request)
   {
       $this->check_authorizations();
       $this->init();
       return $this->build_response($this->template);
   }

   private function init()
   {
       $this->template = new FileTemplate('agenda/AgendaDocumentationController.tpl');
       $this->lang = LangLoader::get('common', 'agenda');
       $this->template->add_lang($this->lang);
   }

   private function check_authorizations()
   {
       if (!AgendaAuthorizationsService::check_authorizations()->read())
       {
           $error_controller = PHPBoostErrors::user_not_authorized();
           DispatchManager::redirect($error_controller);
       }
   }

   private function build_response(View $view)
   {
       $config = AgendaConfig::load();
       $response = new SiteDisplayResponse($view);
       $graphical_environment = $response->get_graphical_environment();
       $graphical_environment->set_page_title($this->lang['agenda.documentation.title']);
       $breadcrumb = $graphical_environment->get_breadcrumb();
       $breadcrumb->add($this->lang['module_title'], AgendaUrlBuilder::home());
       $breadcrumb->add($this->lang['agenda.documentation.title']);
       $this->template->put_all(array(
           'DOCUMENTATION_TITLE' => $this->lang['agenda.documentation.title'],
           'DOCUMENTATION_CONTENT' => FormatingHelper::second_parse($config->get_documentation())
       ));
       return $response;
   }
}
?>
