<?php

/*This program is free software; you can redistribute it and/or modify
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
// define('PATH_TO_ROOT', '../');
//Début du chargement de l'environnement
require_once('../kernel/init.php');

//Titre de la page, ici Accueil
define('TITLE', 'Radio Player');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;

    $env = new SiteDisplayFrameGraphicalEnvironment();
	Environment::set_graphical_environment($env);

    $tpl = new FileTemplate('radio/RadioPlayer.tpl');
    $config = RadioConfig::load();

    if ($config->is_radio_autoplay() == 0)
	{
		$autoplay = '';
	} else
	{
		$autoplay = 'autoplay';
	}

    $tpl->put_all(array(
        "C_AUTOPLAY" => $autoplay,
        "U_NETWORK" => $config->get_radio_url()->rel(),
        "C_IMG" => !empty($config->get_radio_img()),
        "U_RADIO_IMG" => $config->get_radio_img()->rel()
    ));
    $tpl->display();
?>

 <?php
require_once('../kernel/footer_no_display.php');
?>
