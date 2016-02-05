<?php

namespace Foowd\Action;

class FoowdUpdateUser{


	/**
	 * controlli d'inizializzazione
	 * @return [type] [description]
	 */
	public function foowd_user_extra_update(){
		// provo a intercettare forms/account/settings.php
		$call = get_input('foowd_user_settings_update', false);
		// se non e' un'azione impostata da noi di foowd, gli faccio svolgere il suo normale iter interrompendo il resto del codice di questa funzione
		if(!$call) return;

		$me = elgg_get_logged_in_user_entity();
		$ownerGuid = get_input('foowd_user_to_update_guid', false);

		// in ogni caso DEVO salvare i dati di API Foowd, pertanto questo procello e' obbligatorio
		
		// Devo assicurarmi che l'operazione venga svolta o dall'utente che e' owner, o da un'amministratore
		if(!$me->isAdmin() && $me->guid != $ownerGuid) return false;
		// \Uoowd\Logger::addError('salvo');
		$this->updateApiDB($ownerGuid);
	}


	/**
	 * funzione vera e propria: raccolgo i nuovi dati e li salvo sul DB API.
	 *
	 * Se ritorna false allora interrompe il salvataggio, altrimenti i dati dell'utente vengono memorizzati mediante l'iter di elgg
	 * 
	 * @param  [type] $ownerGuid [description]
	 * @return [type]            [description]
	 */
	public function updateApiDB($ownerGuid){

		// Se il genere cambia passando a 'offerente', viene mandata una mail di notifica all'utente per avvisarlo che ora e' un produttore.
		$user = get_entity($ownerGuid);
		$beforeGenre = $user->Genre;

		$form = 'foowd-dati';

		$elgg = array( 'Name', 'Email');

		// nel form di partenza l'emain e' definita con la lettera minuscola perche' salva lato elgg, io invece salvo lato API
		if(get_input('email', false)) set_input('Email', get_input('email') );

		// aggiornamento API
		$foowd = \Foowd\User::$allUserFields;


		foreach($foowd as $val){
			if(get_input($val)) $data[$val] = get_input($val);
		}
		// \Uoowd\Logger::addError($data);

		$data['type'] = 'update';
		$data['ExternalId'] = $ownerGuid;

		$r = \Uoowd\API::Request('user', 'POST', $data);
		// \Uoowd\Logger::addError($r);
		if($r->response){
			// aggiorno il campo dell'utente: l'unico che non avviene tramite interfaccia
			$body = $r->body;
			// aggiorno i dati dell'utente
			$user->Genre = $body->Genre;
			// impongo che il nome visualizzato e lo username siano identici
			// predispongo il dato per il salvataggio: questo e' un semplice hook, ed in secondo step viene ad essere aggiornato dal core di elgg
			set_input('name', $user->username);
			$user->save();
			if($beforeGenre != $body->Genre && $body->Genre == 'offerente'){
				$v['email'] = $user->email;
				$v['username'] = $user->username;
				$this->userApprovedMail($v);
			}

			elgg_clear_sticky_form($form);
			system_message(elgg_echo("Hai aggiornato con successo i tuoi dati."));
			
		}else{
			$_SESSION['sticky_forms'][$form]['apiError']=$r;

			// nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
			if(! $str = \Uoowd\Param::dbg()){ 
				$str = 'Non riesco ad aggiornare i dati';
			}
			register_error(elgg_echo($str));
			\Uoowd\Logger::addError($r);
		}

	}


	/**
	 * email da mandare al produttore una volta approvato
	 * @param  [type] $v [description]
	 * @return [type]    [description]
	 */
	public function userApprovedMail($v){
	
		$txt='
		Salve %s,


		ti informiamo con piacere che la tua richiesta &egrave; stata approvata: benvenuto nella cerchia di produttori di FOOWD.

		Ora potrai accedere a tutte le funzionalit&agrave; "produttore" del pannello d\'amministrazione utente e proporre al pubblico i tuoi articoli.

		Cordialmente, 
		Foowd
		';

		extract($v);

		$from = elgg_get_config('sitename');
		$to = $email;
		$subject = 'Richiesta "produttore" approvata';
		$body = sprintf($txt, $username);
		elgg_send_email($from, $to, $subject, $body, array());

	}


	/**
	 * Hook per mandare una mail di notifica agli amministratori una volta confermata la ricezione della prima mail da parte dell'utente/offerente
	 * @param  [type] $hook   [description]
	 * @param  [type] $type   [description]
	 * @param  [type] $value  [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function foowd_user_confirm_notify_admins($userId){
		$user = get_entity($userId);
		if($user->Genre == 'evaluating'){
			$ar = array();

			$ar['subject'] = 'Notifica proposta nuovo produttore.';
			$txt='
			L\'utente con id %s si e\' iscritto proponendosi come produttore.
			Puoi visualizzare i dati da lui immessi andando nell\'apposita sezione del sito.
			';
			$ar['body'] = sprintf($txt, $userId);
			\Uoowd\Utility::mailToAdmins($ar);
		}
	}


}