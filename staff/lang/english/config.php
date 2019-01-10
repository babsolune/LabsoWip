<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 05
 * @since   	PHPBoost 5.1 - 2017 06 29
*/

 ####################################################
 #						English						#
 ####################################################

$lang['default.role'] = 'President';
$lang['root_category_description'] = 'Welcome to the organizational charts section of the site!
<br /><br />
One category and one member were created to show you how this module works. Here are some tips to get started on this module.
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> To configure or customize the module homepage, go into the <a href="' . StaffUrlBuilder::configuration()->relative() . '">module administration</a></li>
	<li class="formatter-li"> To create categories, <a href="' . StaffUrlBuilder::add_category()->relative() . '">clic here</a></li>
	<li class="formatter-li"> To create members, <a href="' . StaffUrlBuilder::add()->relative() . '">clic here</a></li>
</ul>
<br />To learn more, please consult the documentation for the module on the <a href="http://www.phpboost.com">PHPBoost</a> website.';
?>
