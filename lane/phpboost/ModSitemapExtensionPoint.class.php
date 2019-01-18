<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL [ben.popeye@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 3.0 - 2010 06 13
 * @contributor Julien BRISWALTER [j1.seth@phpboost.com]
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

namespace Wiki\phpboost;
use \Wiki\services\ModServices;
use \Wiki\util\ModUrlBuilder;

class ModSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(ModServices::get_categories_manager());
	}

	protected function get_category_url(Category $category)
	{
		return ModUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}
}
?>
