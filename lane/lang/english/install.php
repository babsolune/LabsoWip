<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

####################################################
#                     English                      #
####################################################

use \Wiki\util\ModUrlBuilder;

$lang['default.category.name'] = 'First category';
$lang['default.category.description'] = 'Demonstration articles';
$lang['default.item.title'] = 'How to begin with the wiki module';
$lang['default.item.description'] = '';
$lang['default.item.contents'] = 'This brief article will give you some simple tips to take control of this module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">To configure your module, <a href="' . ModUrlBuilder::configuration()->rel() . '">click here</a>
</li><li class="formatter-li">To add categories: <a href="' . ModUrlBuilder::add_category()->rel() . '">click here</a> (categories and subcategories are infinitely)
</li><li class="formatter-li">To add an item: <a href="' . ModUrlBuilder::add_item()->rel() . '">click here</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">To format your article, you can use bbcode language or the WYSIWYG editor (see this <a href="https://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li></ul><br />
<br />
For more information, please consult the module documentation at <a href="https://www.phpboost.com">PHPBoost.com</a>.<br />
<br />
<br />
Enjoy this module.';
?>
