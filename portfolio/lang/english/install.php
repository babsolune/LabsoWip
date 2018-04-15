<?php
/*##################################################
 *                            install.php
 *                            -------------------
 *   begin                : November 29, 2017
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

#####################################################
#                      English			    #
####################################################

$lang['default.category.name'] = 'Test category';
$lang['default.category.description'] = 'Demonstration works presentation';
$lang['default.work.title'] = 'Portfolio module for PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['default.work.description'] = '';
$lang['default.work.contents'] = 'This brief article will give you some simple tips to take control of this module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">To configure your module, <a href="' . PortfolioUrlBuilder::configuration()->rel() . '">click here</a>
</li><li class="formatter-li">To add categories: <a href="' . PortfolioUrlBuilder::add_category()->rel() . '">click here</a> (categories and subcategories are infinitely)
</li><li class="formatter-li">To add a work presentation: <a href="' . PortfolioUrlBuilder::add_item()->rel() . '">click here</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">To format your presentation, you can use bbcode language or the WYSIWYG editor (see this <a href="http://www.phpboost.com/wiki/bbcode">article</a>)<br /> 
</li></ul><br />
<br />
For more information, please see the module documentation on the site <a href="http://www.phpboost.com">PHPBoost</a>.<br />
<br />
<br />
Good use of this module.';

?>
