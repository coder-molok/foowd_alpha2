<?php

namespace Uoowd;

/**
 * Per convenzione, tutti i dati vengono passati e ritornati in formato json
 *
 * Classe che implementa il reale impiego delle api elgg esposte in foowdAPI: 
 *
 * ovvero: richiamare metodi delle api elgg o i rispettivi metodi camelcase di questa classe conduce al medesimo effetto
 */

class APIFoowd{

	public static function foowdUserFriendsOf($guid){
		// if($debug) \Uoowd\Logger::addError('friendsOf');
		// in primis controllo che l'utente che svolge la richiesta sia loggato
	//	$user = elgg_get_logged_in_user_entity();
		$j['response'] = false;
		$j['userId'] = $guid;
	/*
		if(!$user){
			$j['msg'] = 'Questa richiesta puo\' avvenire solo dal sito e mentre sei loggato';
			return $j;
		}*/
		// ora controllo se l'id e' associata ad un utente esistente
		$user = get_user($guid);
		if(!$user){
			$j['msg'] = 'Utente inesistente';
			return $j;
		}
		$j['response'] = true;
		$entities = elgg_get_entities_from_relationship(array(
		    'relationship' => 'friend',
		    'relationship_guid' => $guid,
		));
		$j['friends'] = array();
		foreach ($entities as $ent) {
			if($ent->type === 'user') $j['friends'][] = $ent->guid;
		}
		return $j;
	}


}