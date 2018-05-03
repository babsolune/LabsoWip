<?php
/*##################################################
 *                            common.php
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

#####################################################
#                      English			    #
#####################################################

// Titles
$lang['tsm.module.title'] = 'Tsm';
$lang['tsm.item'] = 'Item';
$lang['tsm.items'] = 'Items';
$lang['module.config.title'] = 'Tsm configuration';
$lang['tsm.management'] = 'Tsm management';
$lang['tsm.add'] = 'Add an item';
$lang['tsm.edit'] = 'Item edition';
$lang['tsm.feed.name'] = 'Last items';
$lang['tsm.pending.items'] = 'Pending items';
$lang['tsm.published.items'] = 'Published items';
$lang['tsm.print.item'] = 'Print an item';

//Tsm configuration
$lang['tsm.configuration.cats.icon.display'] = 'Categories icon dipslay';
$lang['tsm.configuration.sort.filters.display'] = 'Sort filters display';
$lang['tsm.configuration.suggestions.display'] = 'Items suggestions display';
$lang['tsm.configuration.suggestions.nb'] = 'Suggested items number';
$lang['tsm.configuration.navigation.links.display'] = 'Navigation links display';
$lang['tsm.configuration.navigation.links.display.desc'] = 'Previous link, next link';
$lang['tsm.configuration.characters.number.to.cut'] = 'Maximum number of characters to cut the item\'s description';
$lang['tsm.configuration.display.type'] = 'Display type';
$lang['tsm.configuration.mosaic.type.display'] = 'Mosaic';
$lang['tsm.configuration.list.type.display'] = 'List';
$lang['tsm.configuration.table.type.display'] = 'Table';
$lang['tsm.configuration.display.descriptions.to.guests'] = 'Display condensed items to guests if they don\'t have read authorization';

//Form
$lang['tsm.form.description'] = 'Description (maximum :number characters)';
$lang['tsm.form.enabled.description'] = 'Enable item description';
$lang['tsm.form.enabled.description.description'] = 'or let PHPBoost cut the content at :number characters';
$lang['tsm.form.carousel'] = 'Ajouter un carousel d\'images';
$lang['tsm.form.image.description'] = 'Description';
$lang['tsm.form.image.url'] = 'Adresse image';
$lang['tsm.form.enabled.author.name.customisation'] = 'Personalize author name';
$lang['tsm.form.custom.author.name'] = 'Custom author name';

//Sort fields title and mode
$lang['tsm.sort.field.views'] = 'Views';
$lang['admin.tsm.sort.field.published'] = 'Published';

//SEO
$lang['tsm.seo.description.root'] = 'All :site\'s items.';
$lang['tsm.seo.description.tag'] = 'All :subject\'s items.';
$lang['tsm.seo.description.pending'] = 'All pending items.';

//Messages
$lang['tsm.message.success.add'] = 'The item <b>:title</b> has been added';
$lang['tsm.message.success.edit'] = 'The item <b>:title</b> has been modified';
$lang['tsm.message.success.delete'] = 'The item <b>:title</b> has been deleted';
?>
