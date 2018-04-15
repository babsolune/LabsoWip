<?php
/*##################################################
 *                            common.php
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
#####################################################

// Titles
$lang['portfolio.module.title'] = 'Portfolio';
$lang['portfolio.item'] = 'Item';
$lang['portfolio.items'] = 'Items';
$lang['module.config.title'] = 'Portfolio configuration';
$lang['portfolio.management'] = 'Portfolio management';
$lang['portfolio.add'] = 'Add an item';
$lang['portfolio.edit'] = 'Item edition';
$lang['portfolio.feed.name'] = 'Last items';
$lang['portfolio.pending.items'] = 'Pending items';
$lang['portfolio.published.items'] = 'Published items';
$lang['portfolio.print.item'] = 'Print an item';
$lang['portfolio.summary'] = 'Summary';
$lang['portfolio.visit'] = 'Visit website';
$lang['portfolio.visits.number'] = 'Visits number';
$lang['portfolio.file'] = 'Download file';
$lang['portfolio.downloads.number'] = 'Downloads number';
$lang['portfolio.external.file'] = 'External website';
$lang['portfolio.dead.link'] = 'Declare a dead link';

//Portfolio configuration
$lang['portfolio.configuration.cats.icon.display'] = 'Categories icon dipslay';
$lang['portfolio.configuration.sort.filters.display'] = 'Sort filters display';
$lang['portfolio.configuration.suggestions.display'] = 'Items suggestions display';
$lang['portfolio.configuration.suggestions.nb'] = 'Suggested items number';
$lang['portfolio.configuration.navigation.links.display'] = 'Navigation links display';
$lang['portfolio.configuration.navigation.links.display.desc'] = 'Previous link, next link';
$lang['portfolio.configuration.characters.number.to.cut'] = 'Maximum number of characters to cut the item\'s description';
$lang['portfolio.configuration.display.type'] = 'Display type';
$lang['portfolio.configuration.mosaic.type.display'] = 'Mosaic';
$lang['portfolio.configuration.list.type.display'] = 'List';
$lang['portfolio.configuration.table.type.display'] = 'Table';
$lang['portfolio.configuration.display.descriptions.to.guests'] = 'Display condensed items to guests if they don\'t have read authorization';

//Form
$lang['portfolio.form.description'] = 'Description (maximum :number characters)';
$lang['portfolio.form.enabled.description'] = 'Enable item description';
$lang['portfolio.form.enabled.description.description'] = 'or let PHPBoost cut the content at :number characters';
$lang['portfolio.form.carousel'] = 'Add a picture carousel';
$lang['portfolio.form.image.description'] = 'Description';
$lang['portfolio.form.add.page'] = 'Insert a page';
$lang['portfolio.form.add.page.title'] = 'New page title';
$lang['portfolio.form.image.url'] = 'Adresse image';
$lang['portfolio.form.enabled.author.name.customisation'] = 'Personalize author name';
$lang['portfolio.form.enable.links.visibility'] = 'Enable visitors to see the links';
$lang['portfolio.form.custom.author.name'] = 'Custom author name';
$lang['portfolio.form.website.url'] = 'Website url';
$lang['portfolio.form.file.url'] = 'File url';
$lang['portfolio.form.reset.downloads.number'] = 'Reset downloads counter';

//Sort fields title and mode
$lang['portfolio.sort.field.views'] = 'Views';
$lang['admin.portfolio.sort.field.published'] = 'Published';

//SEO
$lang['portfolio.seo.description.root'] = 'All :site\'s items.';
$lang['portfolio.seo.description.tag'] = 'All :subject\'s items.';
$lang['portfolio.seo.description.pending'] = 'All pending items.';

//Messages
$lang['portfolio.message.success.add'] = 'The item <b>:title</b> has been added';
$lang['portfolio.message.success.edit'] = 'The item <b>:title</b> has been modified';
$lang['portfolio.message.success.delete'] = 'The item <b>:title</b> has been deleted';
?>
