<?php
/*##################################################
 *                        common.php
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

 ####################################################
 #                      French					    #
 ####################################################

// Titles
$lang['smallads.module.title'] = 'Petites Annonces';
$lang['smallads.item'] = 'Annonce';
$lang['smallads.items'] = 'Annonces';
$lang['smallads.management'] = 'Gestion des annonces';
$lang['smallads.add'] = 'Ajouter une annonce';
$lang['smallads.edit'] = 'Modification d\'une annonce';
$lang['smallads.feed.name'] = 'Derniers annonces';
$lang['smallads.pending.items'] = 'Annonces en attente';
$lang['smallads.member.items'] = 'Mes annonces';
$lang['smallads.published.items'] = 'Annonces publiées';

$lang['smallads.category.list'] = 'Catégories';
$lang['smallads.select.category'] = 'Sélectionnez une catégorie';

$lang['smallads.sold.item'] = 'Terminé';
$lang['smallads.ad.type'] = 'Type';
$lang['smallads.category'] = 'Catégorie';

$lang['smallads.publication.date'] = 'Publié depuis';
$lang['smallads.contact'] = 'Contacter l\'auteur';
$lang['smallads.contact.email'] = 'par email';
$lang['smallads.contact.pm'] = 'par messagerie privée';

//Smallads configuration
$lang['config.title'] = 'Configuration des annonces';
$lang['config.items.default.sort'] = 'Ordre d\'affichage des éléments par défaut';
$lang['config.cats.icon.display'] = 'Afficher l\'icône des catégories';
$lang['config.max.weeks.number.displayed'] = 'Limiter le nombre de semaines d\'affichage';
$lang['config.max.weeks.number'] = 'Nombre de semaines d\'affichage';
$lang['config.display.delay.before.delete'] = 'Delai d\'affichage avant suppression';
$lang['config.display.delay.before.delete.desc'] = 'lorsque la case à cocher "annonce terminée" est activée (en jours)';
$lang['config.display.email.enabled'] = 'Autoriser le lien vers l\'email de l\'auteur';
$lang['config.display.pm.enabled'] = 'Autoriser le lien vers la messagerie privée de l\'auteur';
$lang['config.display.phone.enabled'] = 'Autoriser l\'affichage du numéro de téléphone de l\'auteur';
$lang['config.sort.filter.display'] = 'Afficher les filtres de tri';
$lang['config.suggestions.display'] = 'Afficher les suggestions d\'annonces';
$lang['config.suggestions.nb'] = 'Nombre d\'annonces suggérées à afficher';
$lang['config.module.mini.items.nb'] = 'Nombre d\'annonces à afficher dans le mini module';
$lang['config.navigation.links.display'] = 'Afficher la navigation des annonces connexes';
$lang['config.navigation.links.display.desc'] = 'Lien précédent, lien suivant';
$lang['config.characters.number.to.cut'] = 'Nombre de caractères pour couper le condensé de l\'annonce';
$lang['config.display.type'] = 'Type d\'affichage des annonces';
$lang['config.mosaic.type.display'] = 'Mosaïque';
$lang['config.list.type.display'] = 'Liste';
$lang['config.table.type.display'] = 'Tableau';
$lang['config.display.descriptions.to.guests'] = 'Afficher le condensé des annonces aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';

//Smallads Usage Terms Conditions
$lang['config.usage.terms'] = 'Gestion des CGU';
$lang['smallads.usage.terms'] = 'Conditions générales d\'utilisation';
$lang['config.usage.terms.displayed'] = 'Afficher les CGU';

//Smallads filters configuration
$lang['smallads.filters.config'] = 'Gestion des filtres';
$lang['smallads.type.add'] = 'Ajouter des types d\'annonce';
$lang['smallads.type.placeholder'] = 'Vente, achat, location ...';
$lang['smallads.brand.add'] = 'Ajouter des marques';
$lang['smallads.brand.placeholder'] = 'Nom de la marque';

//Form
$lang['smallads.form.add'] = 'Ajouter une annonce';
$lang['smallads.form.edit'] = 'Modifier une annonce';
$lang['smallads.form.description'] = 'Description (maximum :number caractères)';
$lang['smallads.form.enabled.description'] = 'Activer le condensé de l\'annonce';
$lang['smallads.form.enabled.description.description'] = 'ou laissez PHPBoost couper le contenu à :number caractères';
$lang['smallads.form.price'] = 'Prix';
$lang['smallads.form.price.desc'] = 's\'il est égal à 0, il ne sera pas affiché<br />Mettez une virgule pour les décimales';
$lang['smallads.form.carousel'] = 'Ajouter un carrousel d\'images';
$lang['smallads.form.image.description'] = 'Description';
$lang['smallads.form.image.url'] = 'Adresse image';
$lang['smallads.form.contact'] = 'Coordonnées de contact';
$lang['smallads.form.max.weeks'] = 'Nombre de semaine d\'affichage';
$lang['smallads.form.displayed.author.pm'] = 'Afficher le lien vers la messagerie privée';
$lang['smallads.form.displayed.author.email'] = 'Afficher le lien vers l\'email';
$lang['smallads.form.enabled.author.email.customisation'] = 'Personnaliser l\'email';
$lang['smallads.form.enabled.author.email.customisation.desc'] = 'si vous voulez être contacté sur un autre email que celui de votre compte';
$lang['smallads.form.custom.author.email'] = 'Email de contact';
$lang['smallads.form.displayed.author.phone'] = 'Afficher le numéro de téléphone';
$lang['smallads.form.author.phone'] = 'Numéro de téléphone';
$lang['smallads.form.enabled.author.name.customisation'] = 'Personnaliser le nom de l\'auteur';
$lang['smallads.form.custom.author.name'] = 'Nom de l\'auteur personnalisé';
$lang['smallads.form.sold'] = 'Déclarer cette annonce terminée';
$lang['smallads.form.sold.warning'] = 'L\'annonce sera supprimée après :delay jours<br /><span style="color:#CC0000">Cette action est irréversible</span>';

$lang['smallads.form.smallad.type'] = 'Type d\'annonce';
$lang['smallads.form.smallads.types'] = 'Types d\'annonces';
$lang['smallads.form.member.edition'] = 'Modification par l\'auteur';
$lang['smallads.form.member.edition.explain'] = 'La modification d\'une annonce n\'envoie pas de notification à l\'administrateur pour l\'instant, mais elle sera mise en attente lorsque vous la validerez.
<br /><span class="error">Vous devez alerter un administrateur par messagerie privée de votre modification pour que celle-ci soit publiée.</span>';

//Sort fields title and mode
$lang['smallads.sort.field.views'] = 'Vues';
$lang['admin.smallads.sort.field.published'] = 'Publié';

//SEO
$lang['smallads.seo.description.root'] = 'Toutes les annonces du site :site.';
$lang['smallads.seo.description.tag'] = 'Toutes les annonces sur le sujet :subject.';
$lang['smallads.seo.description.pending'] = 'Toutes les annonces en attente.';

//Messages
$lang['smallads.message.success.add'] = 'L\'annonce <b>:title</b> a été ajoutée';
$lang['smallads.message.success.edit'] = 'L\'annonce <b>:title</b> a été modifiée';
$lang['smallads.message.success.delete'] = 'L\'annonce <b>:title</b> a été supprimée';
$lang['smallads.no.type'] = '<div class="warning">Vous devez déclarer les types d\'annonces (Vente, Achat, ...) dans la <a href="'. PATH_TO_ROOT . SmalladsUrlBuilder::filters_configuration()->relative() . '">gestion des filtres</a></div>';
$lang['smallads.all.types.filters'] = 'Toutes';

$lang['smallads.tel.modal'] = 'Vous devez être connecté pour voir le numéro de téléphone';
$lang['smallads.email.modal'] = 'Vous devez être connecté pour contacter l\'auteur de cette annonce';
$lang['smallads.message.success.email'] = 'Votre message a bien été envoyé';
$lang['smallads.message.error.email'] = 'Une erreur est survenue lors de l\'envoi de votre message';
$lang['email.smallad.contact'] = 'Contacter l\'auteur de l\'annonce';
$lang['email.smallad.title'] = 'Vous êtes intéressé par l\'annonce :';
$lang['email.sender.name'] = 'Votre nom :';
$lang['email.sender.email'] = 'Votre adresse email :';
$lang['email.sender.message'] = 'Votre message :';

$lang['mini.last.smallads'] = 'Dernières annonces';
$lang['mini.no.smallad'] = 'Aucune annonce disponible';
$lang['mini.there.is'] = 'Il y a';
$lang['mini.there.are'] = 'Il y a';
$lang['mini.one.smallad'] = 'annonce sur le site';
$lang['mini.several.smallads'] = 'annonces sur le site';
?>
