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

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once 'MattermostCmd.class.php';

class Mattermost extends eqLogic {
	/*	 * *************************Attributs****************************** */


	/*	 * ***********************Methode static*************************** */


	/**
	public static function cron() {
	}
	 */

	/**
	public static function cron5() {
	}
	 */

	/*
	 * Fonction exécutée automatiquement toutes les heures par Jeedom
	  public static function cronHourly() {

	  }
	 */

	/*
	 * Fonction exécutée automatiquement tous les jours par Jeedom
	  public static function cronDaily() {

	  }
	 */


	/*	 * *********************Méthodes d'instance************************* */

	public function preInsert() {
		
	}

	public function postInsert() {
		
	}

	public function createCmd($name, $type = 'info', $subtype = 'string', $icon = false, $generic_type = null, $configurationList = [], $displayList = []) {
		$cmd = $this->getCmd(null, $name);
		if (!is_object($cmd)) {
			$cmd = new MattermostCmd();
			$cmd->setLogicalId($name);
			$cmd->setName(__($name, __FILE__));
		}
		$cmd->setType($type);
		$cmd->setSubType($subtype);
		$cmd->setGeneric_type($generic_type);
		if ($icon) {
			$cmd->setDisplay('icon', $icon);
		}
		if($configurationList != null) {
			foreach ($configurationList as $key => $value) {
				$cmd->setConfiguration($key, $value);
			}
		}
		if($displayList != null) {
			foreach ($displayList as $key => $value) {
				$cmd->setDisplay($key, $value);
			}
		}
		$cmd->setEqLogic_id($this->getId());
		return $cmd;
	}

	public function postSave() {
		$displayList = null;
		//$displayList['title_placeholder'] = "Nom du nouveau groupe";
		//$displayList['message_disable'] = "1";
		$this->createCmd('sendMessage', 'action', 'message', false, null, null, $displayList)->save();
	}

	public function preUpdate() {
		
	}

	public function postUpdate() {
		
	}

	public function preRemove() {
	}

	public function postRemove() {
		
	}

	
	/**

	curl -i -X POST -H 'Content-Type: application/json' -d 
	 '{"channel_id":"hovjfhoigre879874789fdfdsfsd", "message":"This is a message from a bot", "props":{"attachments": [{"pretext": "Look some text","text": "This is text"}]}}' -H 'Authorization: Bearer hovjfhoigre879874789fdfdsfsd' https://mattermost.org/api/v4/posts

	 */
	public function sendMessage($title, $message) {
		$url = $this->getConfiguration("webhooks");
		$username = $this->getConfiguration("username");
		if(!empty($username)) {
			$username = '"username": "'.$username.'",';
		}
		$channel = $this->getConfiguration("channel");
		if(!empty($channel)) {
			$channel = '"channel": "'.$channel.'",';
		}
		// TODO use /core/img/jeedom_home_Light.png
		$data = '{
				'.$username.'
				'.$channel.'
				"icon_url": "https://community.jeedom.com/uploads/default/original/2X/4/4f3d72d6cd61cc77298f219fc5ee986d3fb4f735.png",
				"text": "' . $message . '"
				}';
		$curl = curl_init();
		log::add(__CLASS__, 'DEBUG', 'Resultat appel ' . $url . ' : ' . $data);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		// Optional Authentication:
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$header[0] = "Content-Type: application/json";
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result_curl = curl_exec($curl);
		$result = json_decode($result_curl);
		curl_close($curl);
		return $result;
	}

	/*
	 * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
	  public static function postConfig_<Variable>() {
	  }
	 */

	/*
	 * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
	  public static function preConfig_<Variable>() {
	  }
	 */

	/**
	 * Non obligatoire
	 * Obtenir l'état du daemon
	 *
	 * @return [log] message de log
	 *        [state]  ok  Démarré
	 *                  nok Non démarré
	 *        [launchable] ok  Démarrable
	 *                      nok Non démarrable
	 *        [launchable_message] Cause de non démarrage
	 *        [auto]   0 Démarrage automatique désactivé
	 *                  1 Démarrage automatique activé
	 */
	 /**
	public static function deamon_info() {
	}
	*/

	/**
	 * Démarre le daemon
	 *
	 * @param Debug (par défault désactivé)
	 */
	/**
	public static function deamon_start($_debug = false) {
	}
	*/

	/**
	 * Démarre le daemon
	 *
	 * @param Debug (par défault désactivé)
	 */
	 /**
	public static function deamon_stop() {
	}
	*/
/**
	public static function socket_start() {
	}

	public static function socket_stop() {
	}
*/
	/*	 * **********************Getteur Setteur*************************** */
}
