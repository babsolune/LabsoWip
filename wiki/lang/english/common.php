<?php
/*##################################################
 *                            common.php
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
#####################################################

//title
$lang['wiki'] = 'Wiki';
$lang['document'] = 'Document';
$lang['module_config_title'] = 'Wiki configuration';
$lang['wiki_management'] = 'Wiki management';
$lang['wiki.add'] = 'Add an document';
$lang['wiki.edit'] = 'Document edition';
$lang['wiki.feed_name'] = 'Last wiki';
$lang['wiki.pending_wiki'] = 'Pending wiki';
$lang['wiki.published_wiki'] = 'Published wiki';
$lang['wiki.select_page'] = 'Select a page';
$lang['wiki.summary'] = 'Summary :';
$lang['wiki.print.document'] = 'Print an document';

//Wiki configuration
$lang['wiki_configuration.display_icon_cats'] = 'Dipslay categories icon';
$lang['wiki_configuration.number_character_to_cut'] = 'Maximum number of characters to cut the document\'s description';
$lang['wiki_configuration.display_type'] = 'Display type';
$lang['wiki_configuration.display_type.mosaic'] = 'Mosaic';
$lang['wiki_configuration.display_type.list'] = 'List';
$lang['wiki_configuration.display_type.block'] = 'List without image';
$lang['wiki_configuration.display_descriptions_to_guests'] = 'Display condensed wiki to guests if they don\'t have read authorization';

//Form
$lang['wiki.form.description'] = 'Description (maximum :number characters)';
$lang['wiki.form.description_enabled'] = 'Enable document description';
$lang['wiki.form.description_enabled.description'] = 'or let PHPBoost cut the content at :number characters';
$lang['wiki.form.add_page'] = 'Insert a page';
$lang['wiki.form.add_page.title'] = 'New page title';

//SEO
$lang['wiki.seo.description.root'] = 'All :site\'s wiki.';
$lang['wiki.seo.description.tag'] = 'All :subject\'s wiki.';
$lang['wiki.seo.description.pending'] = 'All pending wiki.';

//Messages
$lang['wiki.message.success.add'] = 'The document <b>:title</b> has been added';
$lang['wiki.message.success.edit'] = 'The document <b>:title</b> has been modified';
$lang['wiki.message.success.delete'] = 'The document <b>:title</b> has been deleted';
?>
