<?php
/*##################################################
 *                               config.php
 *                            -------------------
 *   begin                : September 13, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

 ####################################################
 #						French						#
 ####################################################

$lang['root_category_description'] = 'Bienvenue dans l\'espace du site consacré aux partenaires !
<br /><br />
Une catégorie et un partenaire ont été créés pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> Pour configurer ou personnaliser l\'accueil de votre module, rendez vous dans l\'<a href="' . SponsorsUrlBuilder::configuration()->relative() . '">administration du module</a></li>
	<li class="formatter-li"> Pour créer des catégories, <a href="' . SponsorsUrlBuilder::add_category()->relative() . '">cliquez ici</a></li>
	<li class="formatter-li"> Pour ajouter des partenaires, <a href="' . SponsorsUrlBuilder::add()->relative() . '">cliquez ici</a></li>
</ul>
<br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="http://www.phpboost.com">PHPBoost</a>.';

$lang['activities.food'] = 'Alimentation';

$lang['types.platinium'] = 'Platine';
$lang['types.gold'] = 'Or';
$lang['types.silver'] = 'Argent';
$lang['types.bronze'] = 'Bronze';

?>
