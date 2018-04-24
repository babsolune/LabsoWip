<?php
/*##################################################
 *                   AdminSmalladsFiltersConfigController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
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

class AdminSmalladsFiltersConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $comments_config;
	private $content_management_config;

	private $lang;
	private $admin_common_lang;

	/**
	 * @var SmalladsConfig
	 */
	private $config;

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

		return new AdminSmalladsDisplayResponse($tpl, $this->lang['smallads.filters.config']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = SmalladsConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('smallads.filters.config', $this->lang['smallads.filters.config']);
		$form->add_fieldset($fieldset);

		// $fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->lang['config.items.default.sort'], $this->config->get_items_default_sort_field() . '-' . $this->config->get_items_default_sort_mode(), $this->get_sort_options()));

		$fieldset->add_field(new FormFieldCheckbox('display_sort_filters', $this->lang['config.sort.filter.display'], $this->config->are_sort_filters_enabled()));

		$fieldset->add_field(new SmalladsFormFieldSmalladType('smallad_type', $this->lang['smallads.type.add'], $this->config->get_smallad_types()));

		// $fieldset->add_field(new SmalladsFormFieldBrand('smallad_brand', $this->lang['smallads.brand.add'], $this->config->get_brands()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_sort_options()
	{
		$common_lang = LangLoader::get('common');
		$lang = LangLoader::get('common', 'smallads');

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_DATE . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_DATE . '-' . Smallad::ASC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_ALPHABETIC . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_ALPHABETIC . '-' . Smallad::ASC),
			new FormFieldSelectChoiceOption($lang['smallads.sort.field.views'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_NUMBER_VIEWS . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($lang['smallads.sort.field.views'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_NUMBER_VIEWS . '-' . Smallad::ASC),
			new FormFieldSelectChoiceOption($common_lang['author'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_AUTHOR . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($common_lang['author'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_AUTHOR . '-' . Smallad::ASC),
		);

		if ($this->comments_config->are_comments_enabled('smallads'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_NUMBER_COMMENTS . '-' . Smallad::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_NUMBER_COMMENTS . '-' . Smallad::DESC);
		}

		if ($this->content_management_config->is_notation_enabled('smallads'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best_note'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_NOTATION . '-' . Smallad::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best_note'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_NOTATION . '-' . Smallad::DESC);
		}

		return $sort_options;
	}

	private function save()
	{
		// $items_default_sort = $this->form->get_value('items_default_sort')->get_raw_value();
		// $items_default_sort = explode('-', $items_default_sort);
		// $this->config->set_items_default_sort_field($items_default_sort[0]);
		// $this->config->set_items_default_sort_mode(TextHelper::strtolower($items_default_sort[1]));

		if ($this->form->get_value('display_sort_filters'))
			$this->config->enable_sort_filters();
		else
			$this->config->disable_sort_filters();

		$this->config->set_smallad_types($this->form->get_value('smallad_type'));
		// $this->config->set_brands($this->form->get_value('smallad_brand'));

		SmalladsConfig::save();
		SmalladsService::get_categories_manager()->regenerate_cache();
	}
}
?>
