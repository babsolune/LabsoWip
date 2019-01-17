<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

class WikiCategoriesFormController extends AbstractRichCategoriesFormController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return WikiService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return WikiUrlBuilder::manage_categories();
	}

	protected function get_add_category_url()
	{
		return WikiUrlBuilder::add_category(AppContext::get_request()->get_getint('id_parent', 0));
	}

	protected function get_edit_category_url(Category $category)
	{
		return WikiUrlBuilder::edit_category($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return WikiUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module.title', 'common', 'wiki');
	}

	protected function get_options_fields(FormFieldset $fieldset)
	{
		parent::get_options_fields($fieldset);
		$fieldset->add_field(new FormFieldColorPicker('color', LangLoader::get_message('wiki.category.color', 'common', 'wiki'), $this->get_category()->get_color()));
	}

	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_color($this->form->get_value('color'));
	}

	protected function check_authorizations()
	{
		if (!WikiAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
