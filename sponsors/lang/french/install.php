<?php
/*##################################################
 *                            install.php
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
#                      French			    #
####################################################

$lang['default.category.name'] = 'Catégorie de test';
$lang['default.category.description'] = 'Partenaires de démonstration';
$lang['default.partner.title'] = 'Sponsors pour PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['default.partner.description'] = '';
$lang['default.partner.contents'] = 'Ce premier article va vous donner quelques conseils simples pour prendre en main ce module.<br />
<br />
<ul class="formatter-ul">
	<li class="formatter-li"> Pour configurer ou personnaliser votre module, rendez vous dans la <a href="' . SponsorsUrlBuilder::configuration()->relative() . '">configuration des catégories</a></li>
	<li class="formatter-li"> Pour personnaliser les niveaux de partenariat, rendez vous dans la <a href="' . SponsorsUrlBuilder::configuration()->relative() . '">configuration des annonces</a></li>
	<li class="formatter-li"> Pour configurer ou personnaliser le texte de l\'adhésion des partenaires, rendez vous dans la <a href="' . SponsorsUrlBuilder::membership_terms_configuration()->relative() . '">configuration des CGU</a></li>
	<li class="formatter-li"> Pour créer des catégories, <a href="' . SponsorsUrlBuilder::add_category()->relative() . '">cliquez ici</a> </li>
	<li class="formatter-li"> Pour ajouter des partenaires, <a href="' . SponsorsUrlBuilder::add_item()->relative() . '">cliquez ici</a></li>
</ul>
<ul class="formatter-ul">
<li class="formatter-li">Pour mettre en page vos articles, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a href="http://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li>
</ul>
<br /><br />
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com/wiki/articles">PHPBoost</a>.<br />
<br />
<br />
Bonne utilisation de ce module.
';
$lang['default.partner.type'] = 'Type de test';
$lang['config.membership.terms.conditions'] = '
<h2 class="formatter-title">Devenez partenaire</h2>

<p>Devenir Partenaire du [ NOM ], c\'est vous associer à la performance d\'un [ TYPE D\'ORGANISATION ], ancré dans sa ville, dans sa région, dans le cœur de tous les [ NOM DES HABITANTS ].</p>
<p>Le [NOM], c\'est :</p>
<ul class="formatter-ul">
    <li class="formatter-li">[ CRITÈRE ]
    </li><li class="formatter-li">[ CRITÈRE ]
    </li><li class="formatter-li">[ CRITÈRE ]
</li></ul>
<p>Devenir Partenaire de [ NOM ], c\'est développer votre notoriété, votre visibilité en bénéficiant de la médiatisation de [ NOM ] au niveau local, national et européen.</p>
<p>C\'est aussi partager des moments d\'émotion et de convivialité, notamment à l\'occasion des [ ÉVÉNEMENTS ] de [ NOM ].</p>
<p>Alors pourquoi pas vous? Pourquoi ne pas venir rejoindre le cercle des plus de [ NOMBRE ] partenaires du [ NOM ]?</p>

<h2 class="formatter-title">Découvrez nos offres :</h2>

<h3 class="formatter-title">[ OFFRE 1 ]</h3>
<p>Profitez des [ÉVÉNEMENTS ] pour convier vos clients, salariés et contacts dans les différents espaces que vous offre [ NOM ].</p>
<p>Téléchargez ci-après la plaquette d\'informations -&gt; [ LIEN PLAQUETTE PDF ]</p>

<h3 class="formatter-title">[ OFFRE 2 ]</h3>
<p>Communiquez sur les [ ESPACE PUB 1 ], [ ESPACE PUB 2 ],  [ ESPACE PUB 3 ] …</p>
<p>Téléchargez ci-après la plaquette d\'informations -&gt; [ LIEN PLAQUETTE PDF ]</p>

<h3 class="formatter-title">[ OFFRE 3 ]</h3>
<p>Créez vos événements d\'entreprise au [ NOM ], Offrez-vous l\'intervention de nos consultants ou la présence de nos collaborateurs sur vos opérations…</p>
<p>Téléchargez ci-après la plaquette d\'informations -&gt; [ LIEN PLAQUETTE PDF ]</p>';
?>
