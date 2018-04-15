<?php
/*##################################################
 *                              common.php
 *                            -------------------
 *   begin                : August 20, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

####################################################
# French                                           #
####################################################

//Titre du module
$lang['module_title'] = 'Agenda';
$lang['module_config_title'] = 'Configuration de l\'agenda';

//Messages divers
$lang['agenda.notice.no_planned_event'] = 'Aucun événement prévu';
$lang['agenda.notice.no_current_action'] = 'Aucun événement pour cette date';
$lang['agenda.notice.no_pending_event'] = 'Aucun événement en attente';
$lang['agenda.notice.no_event'] = 'Aucun événement';
$lang['agenda.notice.suscribe.event_date_expired'] = 'L\'événement est terminé, vous ne pouvez pas vous inscrire.';
$lang['agenda.notice.unsuscribe.event_date_expired'] = 'L\'événement est terminé, vous ne pouvez pas vous désinscrire.';
$lang['clic.to.enlarge'] = 'Cliquez pour agrandir';
$lang['clic.to.enlarge.pdf'] = 'Cliquez pour voir le flyer';

//Titres
$lang['agenda.titles.add_event'] = 'Ajouter un événement';
$lang['agenda.titles.edit_event'] = 'Éditer l\'événement';
$lang['agenda.titles.delete_event'] = 'Supprimer l\'événement';
$lang['agenda.titles.delete_occurrence'] = 'L\'occurrence';
$lang['agenda.titles.delete_all_events_of_the_serie'] = 'Tous les événements de la série';
$lang['agenda.titles.event_edition'] = 'Édition de l\'événement';
$lang['agenda.titles.event_removal'] = 'Suppression de l\'événement';
$lang['agenda.titles.events_of'] = 'événements de';
$lang['agenda.titles.event'] = 'événement';
$lang['agenda.titles.recurrence'] = 'Récurrence';
$lang['agenda.titles.repetition'] = 'Répétition';
$lang['agenda.pending'] = 'événements en attente';
$lang['agenda.manage'] = 'Gérer les événements';
$lang['agenda.events_list'] = 'Liste des événements';

//Labels
$lang['agenda.labels.location'] = 'Lieu';
$lang['agenda.labels.location.more'] = 'Complément d\'adresse';
$lang['agenda.labels.created_by'] = 'Créé par';
$lang['agenda.labels.registration_authorized'] = 'Activer le bouton -je suis intéressé-';
$lang['agenda.labels.max_registered_members'] = 'Nombre de participants maximum';
$lang['agenda.labels.max_registered_members.explain'] = '0 pour illimité';
$lang['agenda.labels.remaining_place'] = 'Plus qu\'une place disponible !';
$lang['agenda.labels.remaining_places'] = 'Il ne reste que :missing_number places !';
$lang['agenda.labels.max_participants_reached'] = 'Le nombre de participants maximum a été atteint.';
$lang['agenda.labels.last_registration_date_enabled'] = 'Définir une date limite d\'inscription';
$lang['agenda.labels.last_registration_date'] = 'Dernière date d\'inscription';
$lang['agenda.labels.remaining_day'] = 'Dernier jour pour s\'inscrire !';
$lang['agenda.labels.remaining_days'] = 'Il ne reste que :days_left jours pour s\'inscrire !';
$lang['agenda.labels.registration_closed'] = 'Les inscriptions pour cet événement sont terminées.';
$lang['agenda.labels.repeat_type'] = 'Répéter';
$lang['agenda.labels.repeat_number'] = 'Nombre de répétitions';
$lang['agenda.labels.repeat_times'] = 'fois';
$lang['agenda.labels.repeat.never'] = 'Jamais';
$lang['agenda.labels.repeat.daily'] = 'Tous les jours';
$lang['agenda.labels.repeat.weekly'] = 'Toutes les semaines';
$lang['agenda.labels.repeat.monthly'] = 'Tous les mois';
$lang['agenda.labels.repeat.yearly'] = 'Tous les ans';
$lang['agenda.labels.events_number'] = ':events_number événements';
$lang['agenda.labels.one_event'] = '1 événement';
$lang['agenda.labels.details'] = 'Voir les détails de l\'événement';
$lang['agenda.labels.share'] = 'Partager cet événement';
$lang['agenda.labels.date'] = 'Date';
$lang['agenda.labels.start_date'] = 'Date et heure de début';
$lang['agenda.labels.end_date'] = 'Date et heure de fin';
$lang['agenda.labels.start_time'] = 'Début de l\'événement';
$lang['agenda.labels.end_time'] = 'Fin de l\'événement';
$lang['agenda.labels.contribution.explain'] = 'Vous n\'êtes pas autorisé à créer un événement, cependant vous pouvez en proposer un.';
$lang['agenda.labels.contribution.modification'] = '<span style="color: #478948">Tant que votre contribution n\'est pas validée, vous pouvez la modifier via votre panneau de contribution. Une fois validée, vous pouvez la modifier via le bouton d\'édition.</span>';
$lang['agenda.labels.birthday'] = 'Anniversaire';
$lang['agenda.labels.birthday_title'] = 'Anniversaire de';
$lang['agenda.labels.nb_participants'] = 'Membres intéressés';
$lang['agenda.labels.participants'] = 'Liste des intéressés';
$lang['agenda.labels.no_one'] = 'Personne';
$lang['agenda.labels.suscribe'] = 'Je suis intéressé';
$lang['agenda.labels.unsuscribe'] = 'Je ne suis plus intéressé';
$lang['agenda.labels.contents'] = 'Description';
$lang['agenda.labels.end_date_enabled'] = 'Définir une date de fin';
$lang['agenda.labels.department'] = 'Département';
$lang['agenda.labels.other_department'] = 'Autre département';
$lang['agenda.labels.other_department.explain'] = 'Hors de France';
$lang['agenda.labels.picture'] = 'Affiche / flyer ';
$lang['agenda.labels.picture.explain'] = 'jpg, png, pdf';
$lang['agenda.labels.forum_link'] = 'Lien vers le forum';
$lang['agenda.labels.forum_talk'] = 'On en parle sur le forum';
$lang['agenda.labels.forum_link.explain'] = 'Discutez de votre événement dans le forum';
$lang['agenda.labels.contact_informations'] = 'Contact';
$lang['agenda.labels.path_informations'] = 'Parcours proposés';
$lang['agenda.labels.cancelled'] = 'Annuler l\'événement';
$lang['agenda.labels.event_cancelled'] = 'événement annulé';
$lang['alternative.pdf.link'] = 'Voir l\'affiche de l\'événement : ';

// location
$lang['agenda.labels.place'] = '* Lieu';
$lang['agenda.labels.place.more'] = 'Complément d\'adresse';
$lang['agenda.labels.enter_address'] = 'Entrez une ville';
$lang['agenda.labels.street_address'] = 'Adresse';
$lang['agenda.labels.city'] = 'Ville';
$lang['agenda.labels.state'] = 'Région';
$lang['agenda.labels.department'] = 'Département';
$lang['agenda.labels.postal_code'] = 'Code postal';
$lang['agenda.labels.country'] = 'Pays';

// Contact
$lang['agenda.contact.desc'] = 'Le nom ou l\'adresse du site sont obligatoires pour valider le contact';
$lang['agenda.placeholder.name'] = 'Nom';
$lang['agenda.placeholder.email'] = 'Email';
$lang['agenda.placeholder.phone1'] = 'Téléphone mobile';
$lang['agenda.placeholder.phone2'] = 'Téléphone fixe';
$lang['agenda.placeholder.site'] = 'Site web';

// Path
$lang['agenda.path.desc'] = 'Le type de parcours est obligatoire pour valider le parcours';
$lang['agenda.option.path.type'] = 'Type de parcours';
$lang['agenda.option.path.type.dirt.cycle'] = 'VTT';
$lang['agenda.option.path.type.walk'] = 'Marche';
$lang['agenda.option.path.type.trail'] = 'Course à pied/Trail';
$lang['agenda.option.path.type.road.cycle'] = 'Cyclo route';
$lang['agenda.option.path.type.horse'] = 'Cheval';
$lang['agenda.placeholder.path.length'] = 'Longueur (km)';
$lang['agenda.length.unit'] = 'km';
$lang['agenda.placeholder.path.elevation'] = 'Dénivelé (m)';
$lang['agenda.elevation.unit'] = 'm';
$lang['agenda.option.path.level'] = 'Niveau de difficulté';
$lang['agenda.placeholder.path.none'] = 'Non communiqué';
$lang['agenda.placeholder.path.start'] = 'Débutant';
$lang['agenda.placeholder.path.medium'] = 'Intermédiaire';
$lang['agenda.placeholder.path.sport'] = 'Sportif';
$lang['agenda.placeholder.path.expert'] = 'Expert';

//Administration
$lang['agenda.config.events.management'] = 'Gestion des événements';
$lang['agenda.config.category.color'] = 'Couleur';
$lang['agenda.config.items_number_per_page'] = 'Nombre d\'événements affichés par page';
$lang['agenda.config.event_color'] = 'Couleur des événements';
$lang['agenda.config.members_birthday_enabled'] = 'Afficher les anniversaires des membres';
$lang['agenda.config.birthday_color'] = 'Couleur des anniversaires';

$lang['agenda.authorizations.display_registered_users'] = 'Autorisation d\'afficher la liste des inscrits';
$lang['agenda.authorizations.register'] = 'Autorisation de s\'inscrire à l\'événement';

//SEO
$lang['agenda.seo.description.root'] = 'Tous les événements du site :site.';
$lang['agenda.seo.description.pending'] = 'Tous les événements en attente.';

//Feed name
$lang['agenda.feed.name'] = 'Événements';

//Messages
$lang['agenda.message.success.add'] = 'L\'événement <b>:title</b> a été ajouté';
$lang['agenda.message.success.edit'] = 'L\'événement <b>:title</b> a été modifié';
$lang['agenda.message.success.delete'] = 'L\'événement <b>:title</b> a été supprimé';
//Erreurs
$lang['agenda.error.e_unexist_event'] = 'L\'événement sélectionné n\'existe pas';
$lang['agenda.error.e_invalid_date'] = 'La date entrée est invalide';
$lang['agenda.error.e_invalid_start_date'] = 'La date de début entrée est invalide';
$lang['agenda.error.e_invalid_end_date'] = 'La date de fin entrée est invalide';
$lang['agenda.error.e_user_born_field_disabled'] = 'Le champ <b>Date de naissance</b> n\'est pas affiché dans le profil des membres. Veuillez activer l\'affichage du champ dans la <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Gestion des champs du profils</a> pour permettre aux membres de renseigner leur date de naissance et afficher leur date d\'anniversaire dans l\'agenda.';

// D�partements
$lang['department'] = 'Département';
$lang['other_department'] = 'Autre département';
$lang['other_department.explain'] = 'Hors de France';
$lang['department.01'] = '01 - Ain';
$lang['department.02'] = '02 - Aisne';
$lang['department.03'] = '03 - Allier';
$lang['department.04'] = '04 - Alpes de Hautes-Provence';
$lang['department.05'] = '05 - Hautes-Alpes';
$lang['department.06'] = '06 - Alpes-Maritimes';
$lang['department.07'] = '07 - Ardèche';
$lang['department.08'] = '08 - Ardennes';
$lang['department.09'] = '09 - Ariège';
$lang['department.10'] = '10 - Aube';
$lang['department.11'] = '11 - Aude';
$lang['department.12'] = '12 - Aveyron';
$lang['department.13'] = '13 - Bouches-du-Rhône';
$lang['department.14'] = '14 - Calvados';
$lang['department.15'] = '15 - Cantal';
$lang['department.16'] = '16 - Charente';
$lang['department.17'] = '17 - Charente-Maritime';
$lang['department.18'] = '18 - Cher';
$lang['department.19'] = '19 - Corrèze';
$lang['department.2A'] = '2A - Corse-du-Sud';
$lang['department.2B'] = '2B - Haute-Corse';
$lang['department.21'] = '21 - Côte-d\'Or';
$lang['department.22'] = '22 - Côtes d\'Armor';
$lang['department.23'] = '23 - Creuse';
$lang['department.24'] = '24 - Dordogne';
$lang['department.25'] = '25 - Doubs';
$lang['department.26'] = '26 - Drôme';
$lang['department.27'] = '27 - Eure';
$lang['department.28'] = '28 - Eure-et-Loir';
$lang['department.29'] = '29 - Finistère';
$lang['department.30'] = '30 - Gard';
$lang['department.31'] = '31 - Haute-Garonne';
$lang['department.32'] = '32 - Gers';
$lang['department.33'] = '33 - Gironde';
$lang['department.34'] = '34 - Hérault';
$lang['department.35'] = '35 - Ille-et-Vilaine';
$lang['department.36'] = '36 - Indre';
$lang['department.37'] = '37 - Indre-et-Loire';
$lang['department.38'] = '38 - Isère';
$lang['department.39'] = '39 - Jura';
$lang['department.40'] = '40 - Landes';
$lang['department.41'] = '41 - Loir-et-Cher';
$lang['department.42'] = '42 - Loire';
$lang['department.43'] = '43 - Haute-Loire';
$lang['department.44'] = '44 - Loire-Atlantique';
$lang['department.45'] = '45 - Loiret';
$lang['department.46'] = '46 - Lot';
$lang['department.47'] = '47 - Lot-et-Garonne';
$lang['department.48'] = '48 - Lozère';
$lang['department.49'] = '49 - Maine-et-Loire';
$lang['department.50'] = '50 - Manche';
$lang['department.51'] = '51 - Marne';
$lang['department.52'] = '52 - Haute-Marne';
$lang['department.53'] = '53 - Mayenne';
$lang['department.54'] = '54 - Meurthe-et-Moselle';
$lang['department.55'] = '55 - Meuse';
$lang['department.56'] = '56 - Morbihan';
$lang['department.57'] = '57 - Moselle';
$lang['department.58'] = '58 - Nièvre';
$lang['department.59'] = '59 - Nord';
$lang['department.60'] = '60 - Oise';
$lang['department.61'] = '61 - Orne';
$lang['department.62'] = '62 - Pas-de-Calais';
$lang['department.63'] = '63 - Puy-de-Dôme';
$lang['department.64'] = '64 - Pyrénées-Atlantiques';
$lang['department.65'] = '65 - Hautes-Pyrénées';
$lang['department.66'] = '66 - Pyrénées-Orientales';
$lang['department.67'] = '67 - Bas-Rhin';
$lang['department.68'] = '68 - Haut-Rhin';
$lang['department.69'] = '69 - Rhône';
$lang['department.70'] = '70 - Haute-Saône';
$lang['department.71'] = '71 - Saône-et-Loire';
$lang['department.72'] = '72 - Sarthe';
$lang['department.73'] = '73 - Savoie';
$lang['department.74'] = '74 - Haute-Savoie';
$lang['department.75'] = '75 - Paris';
$lang['department.76'] = '76 - Seine-Maritime';
$lang['department.77'] = '77 - Seine-et-Marne';
$lang['department.78'] = '78 - Yvelines';
$lang['department.79'] = '79 - Deux-Sèvres';
$lang['department.80'] = '80 - Somme';
$lang['department.81'] = '81 - Tarn';
$lang['department.82'] = '82 - Tarn-et-Garonne';
$lang['department.83'] = '83 - Var';
$lang['department.84'] = '84 - Vaucluse';
$lang['department.85'] = '85 - Vendée';
$lang['department.86'] = '86 - Vienne';
$lang['department.87'] = '87 - Haute-Vienne';
$lang['department.88'] = '88 - Vosges';
$lang['department.89'] = '89 - Yonne';
$lang['department.90'] = '90 - Territoire-de-Belfort';
$lang['department.91'] = '91 - Essonne';
$lang['department.92'] = '92 - Hauts-de-Seine';
$lang['department.93'] = '93 - Seine-Saint-Denis';
$lang['department.94'] = '94 - Val-de-Marne';
$lang['department.95'] = '95 - Val-d\'Oise';

//  Documentation
$lang['agenda.documentation'] = 'Documentation';
$lang['agenda.documentation.title'] = 'Documentation du module agenda';
$lang['agenda.documentation.content'] = 'Contenu de la doc en html';
?>
