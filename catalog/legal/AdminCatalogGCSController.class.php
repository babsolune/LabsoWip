<?php
/*##################################################
 *                         AdminCatalogGCSController.class.php
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


class AdminCatalogGCSController  extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;

	/**
	 * @var CatalogConfig
	 */
	private $config;

	/**
	 * @var CatalogModulesList
	 */
	private $modules;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCatalogDisplayResponse($tpl, $this->lang['catalog.gcs.manage']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('gcs', 'catalog');
		$this->config = CatalogConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		//Sticky
        $sticky_fieldset = new FormFieldsetHTML('gcs', $this->lang['catalog.gcs.manage']);
        $form->add_fieldset($sticky_fieldset);

        $sticky_fieldset->add_field(new FormFieldRichTextEditor('gcs_text', $this->lang['catalog.gcs.content.label'], FormatingHelper::second_parse($this->config->get_gcs_text()), array('rows' => 25)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		 $this->config->set_gcs_text($this->form->get_value('gcs_text'));

		CatalogConfig::save();
	}
}
