Ad<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : March 15, 2018
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
$lang['smallads.module.title'] = 'Smallads';
$lang['smallads.item'] = 'Ad';
$lang['smallads.items'] = 'Ads';
$lang['smallads.management'] = 'Smallads management';
$lang['smallads.add'] = 'Add an ad';
$lang['smallads.edit'] = 'Item edition';
$lang['smallads.feed.name'] = 'Last ads';
$lang['smallads.pending.items'] = 'Pending ads';
$lang['smallads.member.items'] = 'My ads';
$lang['smallads.published.items'] = 'Published ads';

$lang['smallads.category.list'] = 'Categories';
$lang['smallads.select.category'] = 'Select a category';

$lang['smallads.sold.item'] = 'Completed';

$lang['smallads.publication.date'] = 'Published for';
$lang['smallads.contact'] = 'Contact the author';
$lang['smallads.contact.email'] = 'by email';
$lang['smallads.contact.pm'] = 'by private message';

//Smallads configuration
$lang['config.title'] = 'Smallads configuration';
$lang['config.items.default.sort'] = 'Default item order display';
$lang['config.cats.icon.display'] = 'Categories icon dipslay';
$lang['config.max.weeks.number.displayed'] = 'Limit the number of weeks to display an ad';
$lang['config.max.weeks.number'] = 'Default number of weeks to display an ad';
$lang['config.sold.delay.display.before.delete'] = 'Display delay before delete';
$lang['config.sold.delay.display.before.delete.desc'] = 'when the "completed" checkbox is enabled (in days)';
$lang['config.display.email.enabled'] = 'Enable the link to the author email';
$lang['config.display.pm.enabled'] = 'Enable the link to the author pm';
$lang['config.display.phone.enabled'] = 'Enable the display to the author phone number';
$lang['config.sort.filter.display'] = 'Display the sort filters';
$lang['config.suggestions.display'] = 'Item suggestions display';
$lang['config.suggestions.nb'] = 'Suggested ad number';
$lang['config.module.mini.items.nb'] = 'Ad number to display in the mini module';
$lang['config.navigation.links.display'] = 'Navigation links display';
$lang['config.navigation.links.display.desc'] = 'Previous link, next link';
$lang['config.characters.number.to.cut'] = 'Maximum number of characters to cut the ad\'s description';
$lang['config.display.type'] = 'Display type';
$lang['config.mosaic.type.display'] = 'Mosaic';
$lang['config.list.type.display'] = 'List';
$lang['config.table.type.display'] = 'Table';
$lang['config.display.descriptions.to.guests'] = 'Display condensed ads to guests if they don\'t have read authorization';

//Smallads Usage Terms Conditions
$lang['config.usage.terms'] = 'Usage terms management';
$lang['smallads.usage.terms'] = 'Usage terms';
$lang['config.usage.terms.displayed'] = 'Display the usage terms';

//Smallads filters configuration
$lang['smallads.filters.config'] = 'Filters management';
$lang['smallads.type.add'] = 'Add types of ad';
$lang['smallads.type.placeholder'] = 'Sale, purchase, leasing ...';
$lang['smallads.brand.add'] = 'Add brands';
$lang['smallads.brand.placeholder'] = 'Brand\'s name';

//Form
$lang['smallads.form.add'] = 'Add an ad';
$lang['smallads.form.edit'] = 'Modify an ad';
$lang['smallads.form.description'] = 'Description (maximum :number characters)';
$lang['smallads.form.enabled.description'] = 'Enable ad description';
$lang['smallads.form.enabled.description.description'] = 'or let PHPBoost cut the content at :number characters';
$lang['smallads.form.price'] = 'Price';
$lang['smallads.form.decimal'] = 'Use a comma for decimals';
$lang['smallads.form.carousel'] = 'Add a picture carousel';
$lang['smallads.form.image.description'] = 'Description';
$lang['smallads.form.image.url'] = 'Picture address';
$lang['smallads.form.contact'] = 'Contact details';
$lang['smallads.form.max.weeks'] = 'Number of weeks of display';
$lang['smallads.form.displayed.author.pm'] = 'Display the link to pm';
$lang['smallads.form.displayed.author.email'] = 'Display the link to email';
$lang['smallads.form.enabled.author.email.customisation'] = 'Customize email';
$lang['smallads.form.enabled.author.email.customisation.desc'] = 'if you want to be contacted on another email than your account one';
$lang['smallads.form.custom.author.email'] = 'Contact email';
$lang['smallads.form.displayed.author.phone'] = 'Display the phone number';
$lang['smallads.form.author.phone'] = 'Phone number';
$lang['smallads.form.enabled.author.name.customisation'] = 'Customize author name';
$lang['smallads.form.custom.author.name'] = 'Custom author name';
$lang['smallads.form.sold'] = 'Declare this ad completed';
$lang['smallads.form.sold.warning'] = 'The ad will be deleted after :delay days <br /><span style="color:#CC0000">This action is irreversible</span>';

//Sort fields title and mode
$lang['smallads.sort.field.views'] = 'Views';
$lang['admin.smallads.sort.field.published'] = 'Published';

//SEO
$lang['smallads.seo.description.root'] = 'All :site\'s ads.';
$lang['smallads.seo.description.tag'] = 'All :subject\'s ads.';
$lang['smallads.seo.description.pending'] = 'All pending ads.';

//Messages
$lang['smallads.message.success.add'] = 'The ad <b>:title</b> has been added';
$lang['smallads.message.success.edit'] = 'The ad <b>:title</b> has been modified';
$lang['smallads.message.success.delete'] = 'The ad <b>:title</b> has been deleted';
$lang['smallads.no.type'] = '<div class="warning">You must declare some ad types in the <a href="'. PATH_TO_ROOT . SmalladsUrlBuilder::filters_configuration()->relative() . '">ad types management</a></div>';
$lang['smallads.all.types.filters'] = 'All';

$lang['smallads.tel.modal'] = 'You must be connected to see the phone number';
$lang['smallads.email.modal'] = 'You must be connected to contact the author of this ad';
$lang['smallads.message.success.email'] = 'Your message have been sent';
$lang['smallads.message.error.email'] = 'An error occurred while sending your message';
$lang['email.smallad.contact'] = 'Contact the ad author';
$lang['email.smallad.title'] = 'You are interested by the ad :';
$lang['email.sender.name'] = 'Your name :';
$lang['email.sender.email'] = 'Your email :';
$lang['email.sender.message'] = 'Your message :';

$lang['mini.last.smallads'] = 'Last ads';
$lang['mini.no.smallad'] = 'No ad available';
$lang['mini.there.is'] = 'There is';
$lang['mini.there.are'] = 'There are';
$lang['mini.one.smallad'] = 'ad on the website';
$lang['mini.several.smallads'] = 'ads on the website';
?>
