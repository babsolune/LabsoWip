<?php
/*##################################################
 *                            install.php
 *                            -------------------
 *   begin                : May 25, 2018
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

#####################################################
#                      English			    #
####################################################

$lang = array();

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
