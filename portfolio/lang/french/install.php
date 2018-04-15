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
#                      French			    #
####################################################

$lang['default.category.name'] = 'Catégorie de test';
$lang['default.category.description'] = 'Présentationss de démonstration';
$lang['default.work.title'] = 'Module Portfolio pour PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['default.work.description'] = '';
$lang['default.work.contents'] = 'Ce bref article va vous donner quelques conseils simples pour prendre en main ce module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">Pour configurer votre module, <a href="' . PortfolioUrlBuilder::configuration()->rel() . '">cliquez ici</a>
</li><li class="formatter-li">Pour ajouter des catégories : <a href="' . PortfolioUrlBuilder::add_category()->rel() . '">cliquez ici</a> (les catégories et sous catégories sont à l\'infini)
</li><li class="formatter-li">Pour ajouter une présentation : <a href="' . PortfolioUrlBuilder::add_item()->rel() . '">cliquez ici</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">Pour mettre en page vos présentations, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a href="http://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li></ul><br />
<br />
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com/wiki/portfolio">PHPBoost</a>.<br />
<br />
<br />
Bonne utilisation de ce module.
';

?>
