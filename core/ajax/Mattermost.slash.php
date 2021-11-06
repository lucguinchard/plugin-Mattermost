<?php

/*
 * This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
 * Copyright (c) 2018 NextDom.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Fichier appelé lorsque le plugin effectue une requête Ajax
 */
//header('Content-Type: application/json; charset=utf-8');
try {
	// Ajoute le fichier du core qui se charge d'inclure tous les fichiers nécessaires
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

	// Ajoute le fichier de gestion des authentifications
	include_file('core', 'authentification', 'php');

	$token_jeedom = config::byKey('slash.token', "Mattermost");
	$all_header = getallheaders();
	if( !empty($all_header['Authorization'])) {
		$authorization = str_replace("Token " ,"", $all_header['Authorization']);
		if($token_jeedom != $authorization) {
			ajax::error("Le token n’est pas bon !");
		}
	} else {
		ajax::error("Il y a pas de Token !");
	}


	$channel_id = $_POST['channel_id'];
	$channel_name = $_POST['channel_name'];
	$command = $_POST['command'];
	$response_url = $_POST['response_url'];
	$team_domain = $_POST['team_domain'];
	$team_id = $_POST['team_id'];
	$text = $_POST['text'];
	$token = $_POST['token'];
	$trigger_id = $_POST['trigger_id'];
	$user_id = $_POST['user_id'];
	$user_name = $_POST['user_name'];
	
	if(!empty($text)) {
		$textTab = explode(" ", $text);
		$number = count($textTab);
		$first = $textTab[0];
	} else {
		$first = "help";
		$number = 1;
	}

//    [channel_id] => channel_id
//    [channel_name] => jeedom
//    [command] => /jeedom
//    [response_url] => https://mattermost.org/hooks/commands/hovjfhoigre879874789fdfdsfsd
//    [team_domain] => team_domain
//    [team_id] => hovjfhoigre879874789fdfdsfsd
//    [text] => BONJOUR
//    [token] => hovjfhoigre879874789fdfdsfsd
//    [trigger_id] => hovjfhoigre879874789fdfdsfsddsdsqdsq=
//    [user_id] => hovjfhoigre879874789fdfdsfsd
//    [user_name] => luc
	
	switch ($textTab[0]) {
		case 'help' :
			echo "Affiche de l’aide";
			break;
		case 'users' :
			echo "#### Liste des utilisateurs\n";
			echo "| Nom                 | Profils                      | Enable   | LastConnection    |\n";
			echo "|:--------------------|:---------------------------------|:-------|:-------|\n";
			$userList = user::all();
			foreach ($userList as $user) {
				echo "| ".$user->getLogin()." | ".$user->getProfils()." | ".$user->getEnable()." | ".$user->getOptions('lastConnection')." |\n";
			}
			break;
		case 'plugins' :
			echo "#### Liste des plugins\n";
			echo "| Nom                 | Description                      | Categorie   | Auteur    |\n";
			echo "|:--------------------|:---------------------------------|:-------|:-------|\n";
			$pluginList = plugin::listPlugin();
			foreach ($pluginList as $plugin) {
				echo "| ".$plugin->getName()." | ".$plugin->getDescription()." | ".$plugin->getCategory()." | ".$plugin->getAuthor()." |\n";
			}
			break;
		case 'call' :
			$call = str_replace("call " ,"", $text);
			$result = interactQuery::tryToReply($call);
			log::add("Mattermost", 'debug', 'TODO: $textTab ' . $textTab[0] . ' PAS CONNU');
			echo $result['reply'];
			break;
		default :
			log::add("Mattermost", 'info', 'TODO: $textTab ' . $textTab[0] . ' PAS CONNU');
			echo "La commande « $textTab[0] » n’est pas connu";
	}

//	echo '{"response_type": "in_channel", "text": "
//   ---
//   #### Weather in Toronto, Ontario for the Week of February 16th, 2016
//
//   | Day                 | Description                      | High   | Low    |
//   |:--------------------|:---------------------------------|:-------|:-------|
//   | Monday, Feb. 15     | Cloudy with a chance of flurries | 3 °C   | -12 °C |
//   | Tuesday, Feb. 16    | Sunny                            | 4 °C   | -8 °C  |
//   | Wednesday, Feb. 17  | Partly cloudly                   | 4 °C   | -14 °C |
//   | Thursday, Feb. 18   | Cloudy with a chance of rain     | 2 °C   | -13 °C |
//   | Friday, Feb. 19     | Overcast                         | 5 °C   | -7 °C  |
//   | Saturday, Feb. 20   | Sunny with cloudy patches        | 7 °C   | -4 °C  |
//   | Sunday, Feb. 21     | Partly cloudy                    | 6 °C   | -9 °C  |
//   '. print_r($_POST, true) . '\n\n
//   '. $token . '
//   ---
//   " }';
	//ajax::success();

	// Lève une exception si la requête n'a pas été traitée avec succès (Appel de la fonction ajax::success());
	//throw new \Exception(__('Aucune méthode correspondante à : ', __FILE__) . $action);
	/* **********Catch exeption*************** */
} catch (\Exception $e) {
	// Affiche l'exception levé à l'utilisateur
	//ajax::error(displayExeption($e), $e->getCode());
}
