<?php
/*##################################################
 *                        common.php
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

 ####################################################
 #                      French					    #
 ####################################################

// Titles
$lang['sponsors.module.title'] = 'Sponsors';
$lang['sponsors.item'] = 'Partenaire';
$lang['sponsors.items'] = 'Partenaires';
$lang['sponsors.management'] = 'Gestion des partenaires';
$lang['sponsors.add'] = 'Ajouter un partenaire';
$lang['sponsors.edit'] = 'Modification d\'un partenaire';
$lang['sponsors.feed.name'] = 'Derniers partenaires';
$lang['sponsors.pending.items'] = 'Partenaires en attente';
$lang['sponsors.member.items'] = 'Mes partenaires';
$lang['sponsors.published.items'] = 'Partenaires publiés';

$lang['sponsors.visit'] = 'Visiter le site';
$lang['no.website'] = 'Aucun site répertorié';
$lang['sponsors.dead.link'] = 'Déclarer un lien mort';

$lang['sponsors.category.select'] = 'Choisir une catégorie';
$lang['sponsors.category.all'] = 'Toutes les catégories';
$lang['sponsors.all'] = 'Toutes';
$lang['sponsors.select.category'] = 'Sélectionnez une catégorie';

$lang['sponsors.category'] = 'Catégorie';

$lang['sponsors.publication.status'] = 'Status';
$lang['sponsors.publication.author'] = 'Auteur';
$lang['sponsors.publication.date'] = 'Créé le';

//Sponsors configuration
$lang['config.categories.title'] = 'Configuration';
$lang['config.new.window'] = 'Ouvrir les liens de visite dans une nouvelle fenêtre';
$lang['sponsors.level.add'] = 'Ajouter des niveaux de partenariat';
$lang['sponsors.level.placeholder'] = 'Premium, Gold, ...';
$lang['sponsors.items.per.tab'] = 'Nombre d\'éléments max affichés par onglet';
$lang['sponsors.items.per.line'] = 'Nombre d\'éléments affichés par ligne';

//Sponsors mini Menu configuration
$lang['config.mini.title'] = 'Configuration du mini menu';
$lang['config.mini.items.nb'] = 'Nombre de partenaires à afficher dans le mini menu';
$lang['config.mini.speed.desc'] = 'en milisecondes';
$lang['config.mini.animation.speed'] = 'Vitesse de défilement';
$lang['config.mini.autoplay'] = 'Autoriser le défilement automatique';
$lang['config.mini.autoplay.speed'] = 'Temps entre chaque défilement';
$lang['config.mini.autoplay.hover'] = 'Autoriser la pause au survol du carrousel';

//Sponsors membership Terms Conditions
$lang['sponsors.membership'] = 'Devenir partenaire';
$lang['config.membership.terms'] = 'Gestion des adhésions';
$lang['sponsors.membership.terms'] = 'Description du texte des adhésions';
$lang['config.membership.terms.displayed'] = 'Afficher les conditions d\'adhésion';
$lang['config.membership.terms.desc'] = 'Description des conditions d\'adhésion';

//Form
$lang['sponsors.form.add'] = 'Ajouter un partenaire';
$lang['sponsors.form.edit'] = 'Modifier un partenaire';
$lang['sponsors.form.website'] = 'Site internet du partenaire';
$lang['sponsors.form.level'] = 'Niveau de partenariat';
$lang['sponsors.form.member.edition'] = 'Modification par l\'auteur';
$lang['sponsors.form.member.contribution.explain'] = 'Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution. La modification est possible à tout moment, tant qu\'elle est en attente d\'approbation, mais aussi lorsqu\'elle sera publiée. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$lang['sponsors.form.member.edition.explain'] = 'Vous êtes sur le point de modifier votre partenaire. Elle va être déplacée dans les partenaires en attente afin d\'être traitée et une nouvelle alerte sera envoyée à un administrateur.';
$lang['sponsors.form.member.edition.description'] = 'Complément de modification';
$lang['sponsors.form.member.edition.description.desc'] = 'Expliquez ce que vous avez modifié pour un meilleur traitement d\'approbation';

//Sort fields title and mode
$lang['sponsors.pagination'] = 'Page {current} sur {pages}';

//SEO
$lang['sponsors.seo.description.root'] = 'Tous les partenaires du site :site.';
$lang['sponsors.seo.description.tag'] = 'Tous les partenaires sur le sujet :subject.';
$lang['sponsors.seo.description.pending'] = 'Tous les partenaires en attente.';

//Messages
$lang['sponsors.message.success.add'] = 'Le partenaire <b>:title</b> a été ajouté';
$lang['sponsors.message.success.edit'] = 'Le partenaire <b>:title</b> a été modifié';
$lang['sponsors.message.success.delete'] = 'Le partenaire <b>:title</b> a été supprimé';
$lang['sponsors.no.type'] = '<div class="warning">Vous devez déclarer les niveaux de partenariat (Premium, Gold, ...) dans la <a href="'. PATH_TO_ROOT . SponsorsUrlBuilder::configuration()->relative() . '">configuration des partenaires</a></div>';
$lang['sponsors.all.types.filters'] = 'Toutes';

// Mini menu
$lang['mini.last.sponsors'] = 'Derniers partenaires';
$lang['mini.no.partner'] = 'Aucun partenaire disponible';
$lang['mini.there.is'] = 'Il y a';
$lang['mini.there.are'] = 'Il y a';
$lang['mini.one.partner'] = 'partenaire sur le site';
$lang['mini.several.sponsors'] = 'partenaires sur le site';
?>
