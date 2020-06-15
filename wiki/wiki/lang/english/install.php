<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

####################################################
#                     English                      #
####################################################

$lang['default.category.name'] = 'First category';
$lang['default.category.description'] = 'Demonstration of an document';
$lang['default.document.title'] = 'How to begin with the wiki module';
$lang['default.document.description'] = '';
$lang['default.document.contents'] = 'This brief document will give you some simple tips to take control of this module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">To configure your module, <a href="' . WikiUrlBuilder::configuration()->rel() . '">click here</a>
</li><li class="formatter-li">To add categories: <a href="' . WikiUrlBuilder::add_category()->rel() . '">click here</a> (categories and subcategories are infinitely)
</li><li class="formatter-li">To add an item: <a href="' . WikiUrlBuilder::add_item()->rel() . '">click here</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">To format your wiki, you can use bbcode language or the WYSIWYG editor (see this <a href="http://www.phpboost.com/wiki/bbcode">document</a>)<br />
</li></ul><br />
<br />
For more information, please see the module documentation on the site <a href="http://www.phpboost.com">PHPBoost</a>.<br />
<br />
<br />
Good use of this module.';
?>
