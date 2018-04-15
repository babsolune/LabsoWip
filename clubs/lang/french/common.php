<?php
/*##################################################
 *                               common.php
 *                            -------------------
 *   begin                : June 23, 2017
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


 ####################################################
 #						French						#
 ####################################################

$lang['module_title'] = 'Liens Clubs';
$lang['module_config_title'] = 'Configuration des liens clubs';
$lang['district'] = 'Comité';
$lang['form.category'] = 'Catégorie';

$lang['clubs.actions.add'] = 'Ajouter un club';
$lang['clubs.add'] = 'Ajout d\'un club';
$lang['clubs.edit'] = 'Modification d\'un club';
$lang['clubs.pending'] = 'Clubs en attente';
$lang['clubs.manage'] = 'Gérer les clubs';
$lang['clubs.management'] = 'Gestion des clubs';

$lang['clubs.details'] = 'Détails du club';
$lang['see.details'] = 'Voir les détails';
$lang['website'] = 'Site internet';
$lang['visit'] = 'Visiter le site';
$lang['no.website'] = 'Aucun site répertorié';
$lang['visited_times'] = 'Visité :number_visits fois';
$lang['visits_number'] = 'Nombre de visites';
$lang['link_infos'] = 'Informations sur le lien';
$lang['club'] = 'Club';
$lang['clubs'] = 'Clubs';
$lang['clubs.contact'] = 'Contacter le club';
$lang['clubs.colors'] = 'Couleurs du club';
$lang['clubs.description'] = 'Description du club';
$lang['stadium.gps'] = 'Coordonnées GPS du stade';
$lang['stadium.lat'] = 'Latitude';
$lang['stadium.lng'] = 'Longitude';

//config
$lang['config.category_display_type'] = 'Affichage des informations dans les catégories';
$lang['config.category_display_type.display_all_content'] = 'Tout le contenu';
$lang['config.category_display_type.display_table'] = 'Tableau';
$lang['config.new.window'] = 'Nouvelle fenêtre';
$lang['config.new.window.desc'] = 'Ouvrir les liens (site, fb, tw, g+) dans une nouvelle fenêtre.';
$lang['config.gmap.api.key'] = 'Clé Google Map';
$lang['config.gmap.api.key.desc'] = '<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend,places_backend&reusekey=true&hl=Fr" target="_blanc">Suivez ce lien</a> pour obtenir une clé Google Map gratuite';
$lang['config.default.address'] = 'Adresse d\'origine';
$lang['config.default.address.desc'] = 'Adresse de départ nécessaire pour calculer les itinéraires vers les autres clubs.';
$lang['config.default.latitude'] = 'Latitude origine';
$lang['config.default.longitude'] = 'Longitude origine';
$lang['config.default.gps.desc'] = 'Coordonnées GPS du club nécessaires pour calculer les itinéraires vers les autres clubs.';

//SEO
$lang['clubs.seo.description.tag'] = 'Tous les liens sur le sujet :subject.';
$lang['clubs.seo.description.pending'] = 'Tous les liens en attente.';

//contribution
$lang['clubs.form.contribution.explain'] = 'Vous n\'êtes pas autorisé à ajouter un lien, cependant vous pouvez en proposer un.';

//Messages
$lang['clubs.message.success.add'] = 'Le lien <b>:name</b> a été ajouté';
$lang['clubs.message.success.edit'] = 'Le lien <b>:name</b> a été modifié';
$lang['clubs.message.success.delete'] = 'Le lien <b>:name</b> a été supprimé';

//  location
$lang['clubs.headquarter.address'] = 'Adresse du siège';
$lang['clubs.headquarter.address.desc'] = 'Remplissez le premier champ, et sélectionnez la valeur dans la liste déroulante, les infos sont envoyées dans les champs suivants.<br /> Modifiez si nécessaire ou remplissez directement les champs suivants.';
$lang['clubs.labels.enter.address'] = 'Entrez une adresse';
$lang['clubs.labels.street.number'] = 'Numéro';
$lang['clubs.labels.street.address'] = 'Rue, route, lieu-dit, ...';
$lang['clubs.labels.city'] = 'Ville';
$lang['clubs.labels.postal.code'] = 'Code Postal';
$lang['clubs.labels.phone'] = 'Téléphone';
$lang['clubs.labels.email'] = 'Email';

// Social Network
$lang['clubs.social.network'] = 'Réseaux sociaux';
$lang['clubs.labels.facebook'] = 'Adresse du compte Facebook <i class="fa fa-fw fa-facebook"></i>';
$lang['clubs.placeholder.facebook'] = 'https://www.facebook.com/...';
$lang['clubs.labels.twitter'] = 'Adresse du compte Twitter <i class="fa fa-fw fa-twitter"></i>';
$lang['clubs.placeholder.twitter'] = 'https://www.twitter.com/...';
$lang['clubs.labels.gplus'] = 'Adresse du compte Google Plus <i class="fa fa-fw fa-google-plus"></i>';
$lang['clubs.placeholder.gplus'] = 'https://plus.google.com/...';

$lang['clubs.stadium.location'] = 'Coordonnées GPS du stade';
$lang['clubs.stadium.location.desc'] = 'Remplissez le champ Adresse ou déplacez le pointeur.<br /> Seul le pointeur est nécessaire.';
$lang['clubs.stadium.address'] = 'Adresse du stade';
$lang['clubs.website.url'] = 'Adresse du site internet';
$lang['clubs.district'] = 'Comité du club';
$lang['clubs.logo'] = 'Logo du club';
$lang['clubs.logo.mini'] = 'Mini logo';
$lang['clubs.logo.mini.desc'] = '<span style="color: #CC0000">Max 32px de large</span><br /> S\'affiche sur la carte générale et le tableau de la liste des clubs.';
$lang['clubs.color.name'] = 'Nom de la couleur';
$lang['clubs.colors'] = 'Couleurs du club';
$lang['clubs.colors.desc'] = 'Le nom doit être renseigné pour valider la couleur.<br />Tous les noms doivent être différents.';

// Alerts
$lang['clubs.no.gmap'] = '<span class="warning">Vous devez installer et/ou activer le module GoogleMaps et le configurer.</span>';
$lang['clubs.no.default.address'] = '<span class="warning">Vous devez déclarer une adresse d\'origine dans la configuration du module.</span>';
$lang['clubs.no.gps'] = '<span class="warning">Les coordonnées GPS du stade n\'ont pas été renseignées.</span>';

?>
