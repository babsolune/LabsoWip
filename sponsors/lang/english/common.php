<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : May 20, 2018
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
$lang['sponsors.module.title'] = 'Sponsors';
$lang['sponsors.item'] = 'Ad';
$lang['sponsors.items'] = 'Ads';
$lang['sponsors.management'] = 'Sponsors management';
$lang['sponsors.add'] = 'Add an ad';
$lang['sponsors.edit'] = 'Item edition';
$lang['sponsors.feed.name'] = 'Last ads';
$lang['sponsors.pending.items'] = 'Pending ads';
$lang['sponsors.member.items'] = 'My ads';
$lang['sponsors.published.items'] = 'Published ads';

$lang['sponsors.category.list'] = 'Categories';
$lang['sponsors.category.select'] = 'Choose a category';
$lang['sponsors.category.all'] = 'All categories';
$lang['sponsors.select.category'] = 'Select a category';

$lang['sponsors.completed.item'] = 'Completed';
$lang['sponsors.ad.type'] = 'Type';
$lang['sponsors.category'] = 'Category';

$lang['sponsors.publication.date'] = 'Published for';
$lang['sponsors.contact'] = 'Contact the author';
$lang['sponsors.contact.email'] = 'by email';
$lang['sponsors.contact.pm'] = 'by private message';

//Sponsors categories configuration
$lang['config.categories.title'] = 'Categories configuration';
$lang['config.cats.icon.display'] = 'Categories icon display';

//Sponsors items configuration
$lang['config.items.title'] = 'Ads configuration';
$lang['config.currency'] = 'Currency';
$lang['sponsors.type.add'] = 'Add types of ad';
$lang['sponsors.type.placeholder'] = 'Sale, purchase, leasing ...';
$lang['sponsors.brand.add'] = 'Add brands';
$lang['sponsors.brand.placeholder'] = 'Brand\'s name';
$lang['config.location'] = 'Activate location';
$lang['config.max.weeks.number.displayed'] = 'Limit the number of weeks of posting';
$lang['config.max.weeks.number'] = 'Default number of weeks of posting';
$lang['config.display.delay.before.delete'] = 'Display delay before delete';
$lang['config.display.delay.before.delete.desc'] = 'when the "completed" checkbox is enabled (in days)';
$lang['config.display.contact.to.visitors'] = 'Allow visitors to contact ad authors';
$lang['config.display.contact.to.visitors.desc'] = 'If not checked, only connected members can contact ad authors';
$lang['config.display.email.enabled'] = 'Enable the link to the author\'s email';
$lang['config.display.pm.enabled'] = 'Enable the link to the author\'s pm';
$lang['config.display.phone.enabled'] = 'Enable the display to the author\'s phone number';
$lang['config.suggestions.display'] = 'Display ad suggestions';
$lang['config.suggestions.nb'] = 'Number of ads to display';
$lang['config.related.links.display'] = 'Display related links to ads';
$lang['config.related.links.display.desc'] = 'Previous link, next link';

// Sponsors mini menu configuration
$lang['config.mini.title'] = 'Mini menu configuration';
$lang['config.mini.items.nb'] = 'Ads number to display mini menu';
$lang['config.mini.speed.desc'] = 'in milisecondes';
$lang['config.mini.animation.speed'] = 'Speed scrolling';
$lang['config.mini.autoplay'] = 'Enable autoplay';
$lang['config.mini.autoplay.speed'] = 'Time between 2 scrolls';
$lang['config.mini.autoplay.hover'] = 'Enable pause on slideshow hover';

//Sponsors Usage Terms Conditions
$lang['config.membership.terms'] = 'Usage terms management';
$lang['sponsors.membership.terms'] = 'Usage terms';
$lang['config.membership.terms.displayed'] = 'Display the membership terms';
$lang['config.membership.terms.desc'] = 'Usage terms description';

//Form
$lang['sponsors.form.add'] = 'Add an ad';
$lang['sponsors.form.edit'] = 'Modify an ad';
$lang['sponsors.form.description'] = 'Description (maximum :number characters)';
$lang['sponsors.form.enabled.description'] = 'Enable ad description';
$lang['sponsors.form.enabled.description.description'] = 'or let PHPBoost cut the content at :number characters';
$lang['sponsors.form.price'] = 'Price';
$lang['sponsors.form.price.desc'] = 'Leave to 0 to not display the price.<br />Use a comma for decimals.';
$lang['sponsors.form.carousel'] = 'Add a picture carousel';
$lang['sponsors.form.image.description'] = 'Description';
$lang['sponsors.form.image.url'] = 'Picture address';
$lang['sponsors.form.contact'] = 'Contact details';
$lang['sponsors.form.max.weeks'] = 'Number of weeks of display';
$lang['sponsors.form.displayed.author.pm'] = 'Display the link to pm';
$lang['sponsors.form.displayed.author.email'] = 'Display the link to email';
$lang['sponsors.form.enabled.author.email.customisation'] = 'Customize email';
$lang['sponsors.form.enabled.author.email.customisation.desc'] = 'if you want to be contacted on another email than your account one';
$lang['sponsors.form.custom.author.email'] = 'Contact email';
$lang['sponsors.form.displayed.author.phone'] = 'Display the phone number';
$lang['sponsors.form.author.phone'] = 'Phone number';
$lang['sponsors.form.enabled.author.name.customisation'] = 'Customize author name';
$lang['sponsors.form.custom.author.name'] = 'Custom author name';
$lang['sponsors.form.completed'] = 'Declare this ad completed';
$lang['sponsors.form.completed.warning'] = '';

$lang['sponsors.form.partner.type'] = 'Type of ad';
$lang['sponsors.form.sponsors.types'] = 'Types of ads';
$lang['sponsors.form.member.edition'] = 'Modification by author';
$lang['sponsors.form.member.contribution.explain'] = 'Your contribution will be sent to pending ads, follow the approval processing in your contribution panel. Modification is possible before and after approbation. You can justify your contribution in the next field.';
$lang['sponsors.form.member.edition.explain'] = 'You are about to modify your ad. It will be sent to pending ads to be processed and a new alert will be sent to administrators';
$lang['sponsors.form.member.edition.description'] = 'Further description of modification';
$lang['sponsors.form.member.edition.description.desc'] = 'Explain what you have modify for a better approval processing';

//SEO
$lang['sponsors.seo.description.root'] = 'All :site\'s ads.';
$lang['sponsors.seo.description.tag'] = 'All :subject\'s ads.';
$lang['sponsors.seo.description.pending'] = 'All pending ads.';

//Messages
$lang['sponsors.message.success.add'] = 'The ad <b>:title</b> has been added';
$lang['sponsors.message.success.edit'] = 'The ad <b>:title</b> has been modified';
$lang['sponsors.message.success.delete'] = 'The ad <b>:title</b> has been deleted';
$lang['sponsors.no.type'] = '<div class="warning">You must declare some ad types in the <a href="'. PATH_TO_ROOT . SponsorsUrlBuilder::configuration()->relative() . '">ads configuration</a></div>';
$lang['sponsors.all.types.filters'] = 'All';

$lang['sponsors.tel.modal'] = 'You must be connected to see the phone number';
$lang['sponsors.email.modal'] = 'You must be connected to contact the author of this ad';
$lang['sponsors.message.success.email'] = 'Your message have been sent';
$lang['sponsors.message.error.email'] = 'An error occurred while sending your message';
$lang['email.partner.contact'] = 'Contact the ad author';
$lang['email.partner.title'] = 'You are interested by the ad :';
$lang['email.sender.name'] = 'Your name :';
$lang['email.sender.email'] = 'Your email :';
$lang['email.sender.message'] = 'Your message :';

$lang['mini.last.sponsors'] = 'Last ads';
$lang['mini.no.partner'] = 'No ad available';
$lang['mini.there.is'] = 'There is';
$lang['mini.there.are'] = 'There are';
$lang['mini.one.partner'] = 'ad on the website';
$lang['mini.several.sponsors'] = 'ads on the website';
?>
