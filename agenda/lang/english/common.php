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
# English                                          #
####################################################

//Module title
$lang['module_title'] = 'Agenda';
$lang['module_config_title'] = 'Agenda configuration';

//Messages divers
$lang['agenda.notice.no_planned_event'] = 'No planned event';
$lang['agenda.notice.no_current_action'] = 'No events scheduled for this date';
$lang['agenda.notice.no_pending_event'] = 'No pending event';
$lang['agenda.notice.no_event'] = 'No event';
$lang['agenda.notice.suscribe.event_date_expired'] = 'The event is finished, you can not suscribe.';
$lang['agenda.notice.unsuscribe.event_date_expired'] = 'The event is finished, you can not unsuscribe.';

//Titles
$lang['agenda.titles.add_event'] = 'Add event';
$lang['agenda.titles.edit_event'] = 'Edit event';
$lang['agenda.titles.delete_event'] = 'Delete event';
$lang['agenda.titles.delete_occurrence'] = 'Occurrence';
$lang['agenda.titles.delete_all_events_of_the_serie'] = 'All events of the serie';
$lang['agenda.titles.event_edition'] = 'Event edition';
$lang['agenda.titles.event_removal'] = 'Event removal';
$lang['agenda.titles.events_of'] = 'Events of';
$lang['agenda.titles.event'] = 'Event';
$lang['agenda.titles.repetition'] = 'Repetition';
$lang['agenda.pending'] = 'Pending events';
$lang['agenda.manage'] = 'Manage events';
$lang['agenda.events_list'] = 'Events list';

//Labels
$lang['agenda.labels.location'] = 'Location';
$lang['agenda.labels.created_by'] = 'Created by';
$lang['agenda.labels.registration_authorized'] = 'Active members registration for the event';
$lang['agenda.labels.remaining_place'] = 'Only one place left!';
$lang['agenda.labels.remaining_places'] = 'Only :missing_number places left!';
$lang['agenda.labels.max_registered_members'] = 'Maximum participant number';
$lang['agenda.labels.max_registered_members.explain'] = '0 for no limit';
$lang['agenda.labels.max_participants_reached'] = 'Maximum participants number has been reached.';
$lang['agenda.labels.last_registration_date_enabled'] = 'Set a limit registration date';
$lang['agenda.labels.last_registration_date'] = 'Last registration date';
$lang['agenda.labels.remaining_day'] = 'Last day to suscribe !';
$lang['agenda.labels.remaining_days'] = 'Only :days_left days left to suscribe !';
$lang['agenda.labels.registration_closed'] = 'Event registration is closed.';
$lang['agenda.labels.repeat_type'] = 'Repeat';
$lang['agenda.labels.repeat_number'] = 'Repeat number';
$lang['agenda.labels.repeat_times'] = 'times';
$lang['agenda.labels.repeat.never'] = 'Never';
$lang['agenda.labels.repeat.daily'] = 'Daily';
$lang['agenda.labels.repeat.weekly'] = 'Weekly';
$lang['agenda.labels.repeat.monthly'] = 'Monthly';
$lang['agenda.labels.repeat.yearly'] = 'Yearly';
$lang['agenda.labels.date'] = 'Date';
$lang['agenda.labels.events_number'] = ':events_number events';
$lang['agenda.labels.one_event'] = '1 event';
$lang['agenda.labels.start_date'] = 'Start date';
$lang['agenda.labels.end_date'] = 'End date';
$lang['agenda.labels.contribution.explain'] = 'You are not authorized to create an event, however you can contribute by submitting one.';
$lang['agenda.labels.birthday'] = 'Birthday';
$lang['agenda.labels.birthday_title'] = 'Birthday of';
$lang['agenda.labels.participants'] = 'Participants';
$lang['agenda.labels.no_one'] = 'No one';
$lang['agenda.labels.suscribe'] = 'Suscribe';
$lang['agenda.labels.unsuscribe'] = 'Unsuscribe';
$lang['agenda.labels.contents'] = 'Description';
$lang['agenda.labels.end_date_enabled'] = 'Enable end date';
$lang['agenda.labels.department'] = 'Department';
$lang['agenda.labels.other_department'] = 'Other department';
$lang['agenda.labels.other_department.explain'] = 'Out of France';
$lang['agenda.labels.picture'] = 'Flyer';
$lang['agenda.labels.forum_link'] = 'Forum link';
$lang['agenda.labels.contact_informations'] = 'Contact';
$lang['agenda.labels.cancelled'] = 'Cancel event';
$lang['agenda.labels.event_cancelled'] = 'Event cancelled';

//Departments
$lang['agenda.department.1'] = '1 - Ain';
$lang['agenda.department.2'] = '2 - Aisne';
$lang['agenda.department.3'] = '3 - Allier';
$lang['agenda.department.4'] = '4 - Alpes de Hautes-Provence';
$lang['agenda.department.5'] = '5 - Hautes-Alpes';
$lang['agenda.department.6'] = '6 - Alpes-Maritimes';
$lang['agenda.department.7'] = '7 - Ardèche';
$lang['agenda.department.8'] = '8 - Ardennes';
$lang['agenda.department.9'] = '9 - Ariège';
$lang['agenda.department.10'] = '10 - Aube';
$lang['agenda.department.11'] = '11 - Aude';
$lang['agenda.department.12'] = '12 - Aveyron';
$lang['agenda.department.13'] = '13 - Bouches-du-Rhône';
$lang['agenda.department.14'] = '14 - Calvados';
$lang['agenda.department.15'] = '15 - Cantal';
$lang['agenda.department.16'] = '16 - Charente';
$lang['agenda.department.17'] = '17 - Charente-Maritime';
$lang['agenda.department.18'] = '18 - Cher';
$lang['agenda.department.19'] = '19 - Corrèze';
$lang['agenda.department.2A'] = '2A - Corse-du-Sud';
$lang['agenda.department.2B'] = '2B - Haute-Corse';
$lang['agenda.department.21'] = '21 - Côte-d\'Or';
$lang['agenda.department.22'] = '22 - Côtes d\'Armor';
$lang['agenda.department.23'] = '23 - Creuse';
$lang['agenda.department.24'] = '24 - Dordogne';
$lang['agenda.department.25'] = '25 - Doubs';
$lang['agenda.department.26'] = '26 - Drôme';
$lang['agenda.department.27'] = '27 - Eure';
$lang['agenda.department.28'] = '28 - Eure-et-Loir';
$lang['agenda.department.29'] = '29 - Finistère';
$lang['agenda.department.30'] = '30 - Gard';
$lang['agenda.department.31'] = '31 - Haute-Garonne';
$lang['agenda.department.32'] = '32 - Gers';
$lang['agenda.department.33'] = '33 - Gironde';
$lang['agenda.department.34'] = '34 - Hérault';
$lang['agenda.department.35'] = '35 - Ille-et-Vilaine';
$lang['agenda.department.36'] = '36 - Indre';
$lang['agenda.department.37'] = '37 - Indre-et-Loire';
$lang['agenda.department.38'] = '38 - Isère';
$lang['agenda.department.39'] = '39 - Jura';
$lang['agenda.department.40'] = '40 - Landes';
$lang['agenda.department.41'] = '41 - Loir-et-Cher';
$lang['agenda.department.42'] = '42 - Loire';
$lang['agenda.department.43'] = '43 - Haute-Loire';
$lang['agenda.department.44'] = '44 - Loire-Atlantique';
$lang['agenda.department.45'] = '45 - Loiret';
$lang['agenda.department.46'] = '46 - Lot';
$lang['agenda.department.47'] = '47 - Lot-et-Garonne';
$lang['agenda.department.48'] = '48 - Lozère';
$lang['agenda.department.49'] = '49 - Maine-et-Loire';
$lang['agenda.department.50'] = '50 - Manche';
$lang['agenda.department.51'] = '51 - Marne';
$lang['agenda.department.52'] = '52 - Haute-Marne';
$lang['agenda.department.53'] = '53 - Mayenne';
$lang['agenda.department.54'] = '54 - Meurthe-et-Moselle';
$lang['agenda.department.55'] = '55 - Meuse';
$lang['agenda.department.56'] = '56 - Morbihan';
$lang['agenda.department.57'] = '57 - Moselle';
$lang['agenda.department.58'] = '58 - Nièvre';
$lang['agenda.department.59'] = '59 - Nord';
$lang['agenda.department.60'] = '60 - Oise';
$lang['agenda.department.61'] = '61 - Orne';
$lang['agenda.department.62'] = '62 - Pas-de-Calais';
$lang['agenda.department.63'] = '63 - Puy-de-Dôme';
$lang['agenda.department.64'] = '64 - Pyrénées-Atlantiques';
$lang['agenda.department.65'] = '65 - Hautes-Pyrénées';
$lang['agenda.department.66'] = '66 - Pyrénées-Orientales';
$lang['agenda.department.67'] = '67 - Bas-Rhin';
$lang['agenda.department.68'] = '68 - Haut-Rhin';
$lang['agenda.department.69'] = '69 - Rhône';
$lang['agenda.department.70'] = '70 - Haute-Saône';
$lang['agenda.department.71'] = '71 - Saône-et-Loire';
$lang['agenda.department.72'] = '72 - Sarthe';
$lang['agenda.department.73'] = '73 - Savoie';
$lang['agenda.department.74'] = '74 - Haute-Savoie';
$lang['agenda.department.75'] = '75 - Paris';
$lang['agenda.department.76'] = '76 - Seine-Maritime';
$lang['agenda.department.77'] = '77 - Seine-et-Marne';
$lang['agenda.department.78'] = '78 - Yvelines';
$lang['agenda.department.79'] = '79 - Deux-Sèvres';
$lang['agenda.department.80'] = '80 - Somme';
$lang['agenda.department.81'] = '81 - Tarn';
$lang['agenda.department.82'] = '82 - Tarn-et-Garonne';
$lang['agenda.department.83'] = '83 - Var';
$lang['agenda.department.84'] = '84 - Vaucluse';
$lang['agenda.department.85'] = '85 - Vendée';
$lang['agenda.department.86'] = '86 - Vienne';
$lang['agenda.department.87'] = '87 - Haute-Vienne';
$lang['agenda.department.88'] = '88 - Vosges';
$lang['agenda.department.89'] = '89 - Yonne';
$lang['agenda.department.90'] = '90 - Territoire-de-Belfort';
$lang['agenda.department.91'] = '91 - Essonne';
$lang['agenda.department.92'] = '92 - Hauts-de-Seine';
$lang['agenda.department.93'] = '93 - Seine-Saint-Denis';
$lang['agenda.department.94'] = '94 - Val-de-Marne';
$lang['agenda.department.95'] = '95 - Val-d\'Oise';

//Administration
$lang['agenda.config.events.management'] = 'Events management';
$lang['agenda.config.category.color'] = 'Color';
$lang['agenda.config.items_number_per_page'] = 'Events number per page';
$lang['agenda.config.event_color'] = 'Events color';
$lang['agenda.config.members_birthday_enabled'] = 'Display members birthday';
$lang['agenda.config.birthday_color'] = 'Birthday color';

$lang['agenda.authorizations.display_registered_users'] = 'Display registered users permissions';
$lang['agenda.authorizations.register'] = 'Register permissions';

//SEO
$lang['agenda.seo.description.root'] = 'All events of :site.';
$lang['agenda.seo.description.pending'] = 'All pending events.';

//Feed name
$lang['agenda.feed.name'] = 'Events';

//Messages
$lang['agenda.message.success.add'] = 'The event <b>:title</b> has been added';
$lang['agenda.message.success.edit'] = 'The event <b>:title</b> has been modified';
$lang['agenda.message.success.delete'] = 'The event <b>:title</b> has been deleted';
//Errors
$lang['agenda.error.e_unexist_event'] = 'The selected event doesn\'t exist';
$lang['agenda.error.e_invalid_date'] = 'Invalid date';
$lang['agenda.error.e_invalid_start_date'] = 'Invalid start date';
$lang['agenda.error.e_invalid_end_date'] = 'Invalid end date';
$lang['agenda.error.e_user_born_field_disabled'] = 'The field <b>Date of birth</b> is not displayed in members profile. Please enable its display it in the <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Profile field management</a> to allow members to fill the field date of birth and display their birthday date in the agenda.';
?>
