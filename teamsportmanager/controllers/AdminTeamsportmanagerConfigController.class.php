<?php
/*##################################################
 *                   AdminTeamsportmanagerConfigController.class.php
 *                            -------------------
 *   begin                : February 13, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
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

class AdminTeamsportmanagerConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $admin_lang;
	private $admin_common_lang;

	/**
	 * @var TeamsportmanagerConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->admin_lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminTeamsportmanagerDisplayResponse($tpl, $this->admin_lang['admin.config']);
	}

	private function init()
	{
		$this->admin_lang = LangLoader::get('admin', 'teamsportmanager');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = TeamsportmanagerConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('teamsportmanager_configuration', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('seasons_cols_nb', $this->admin_lang['admin.seasons.cols.nb'], $this->config->get_seasons_columns_number(),
			array('min' => 1, 'max' => 6, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 6))
		));

		$fieldset->add_field(new FormFieldNumberEditor('competition_cols_nb', $this->admin_lang['admin.competition.cols.nb'], $this->config->get_competitions_columns_number(),
			array('min' => 1, 'max' => 6, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 6))
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->get_seasons_columns_number($this->form->get_value('seasons_cols_nb'));
		$this->config->get_competitions_columns_number($this->form->get_value('competition_cols_nb'));

		TeamsportmanagerConfig::save();
	}
}
?>
