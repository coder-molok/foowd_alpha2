<?php

namespace Foowd\Action;

class FoowdUpdateUser{

	/**
	 * Metadato: se e' presente vuol dire che l'utente ha un change mail da confermare
	 */
	public $emailToSetMetadata = 'emailToSet';
	/**
	 * dopo tre giorni la richiesta espira
	 */
	public $emailExpiration = 'emailExpiration';
	public $daysToExpire = 3 ;

	public $cryptKey = "wjdflsjcoy";


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
		$beforeUsername = $user->username;
		$emailToSet = get_input('email');


		$form = 'foowd-dati';

		// Elgg fa un casino con lo username nel form... per agevolarmi uso la variabile normale
		if(get_input('Username', false)) set_input('username', get_input('Username') );

		// se il name e' vuoto, allora di default lo imposto col valore dello username
		$name = (get_input('name', false)) ?  get_input('name') : get_input('username');
		// per elgg
		set_input('name', $name);
		// l'email non la modifico, perche' per quella mando il giro di email, vedi $this->foowd_change_email_send()
		set_input('email', $user->email);

		// per Foowd
		set_input('Name', $name);
		// nel form di partenza l'emain e' definita con la lettera minuscola perche' salva lato elgg, io invece salvo lato API
		if(get_input('username', false)) set_input('Username', get_input('username') );

		// aggiornamento API
		$foowd = \Foowd\User::$allUserFields;


		foreach($foowd as $val){
			if(get_input($val)) $data[$val] = get_input($val);
		}
		// \Uoowd\Logger::addError($data);

		$data['type'] = 'update';
		$data['ExternalId'] = $ownerGuid;

		// aggiungo il parametro per il vincolo su TUTTE le offerte
		if(get_input('MinOrderPrice', false)){
			// aggiungo due decimali per comodita'
			$price = number_format((float)get_input('MinOrderPrice'), 2, '.', ''); 
			$data['GroupConstraint'] = ['minPrice'=>$price];
		}

		// \Uoowd\Logger::addError($data);
		$r = \Uoowd\API::Request('user', 'POST', $data);
		if($r->response){
			// aggiorno il campo dell'utente: l'unico che non avviene tramite interfaccia
			$body = $r->body;
			// aggiorno i dati dell'utente
			$user->Genre = $body->Genre;
			// impongo che il nome visualizzato e lo username siano identici
			// predispongo il dato per il salvataggio: questo e' un semplice hook, ed in secondo step viene ad essere aggiornato dal core di elgg
			// set_input('name', $user->username);
			// salvo il nuovo username, dato che elgg non lo fa
			if(get_input('username', false) && get_input('username') != '') $user->username = get_input('username');
			$user->save();
			if($beforeGenre != $body->Genre && $body->Genre == 'offerente'){
				$v['email'] = $user->email;
				$v['username'] = $user->username;
				$this->userApprovedMail($v);
			}

			elgg_clear_sticky_form($form);
			system_message(elgg_echo("Hai aggiornato con successo i tuoi dati."));

			// se ho cambiato lo username, lascio che i successivi salvataggi avvengano senza intoppi, 
			// ma wrappo il redirect: tenterebbe to tornare alla pagina attuale, ma avendo cambiato lo username
			// in automatico devo cambiare la pagina
			if($beforeUsername != $user->username){
				$session = elgg_get_session();
				$session->set('foowdForward', 'foowdUserSettingsUsernameChanged');
				$session->set('foowdForwardUserGuid', $user->guid);
			}

			// se la mail e' cambiata , e non ho ancora mandato il messaggio, allora lo mando
			if($emailToSet != $body->Email){
				// se e' presene ma l'ho gia' mandata...
				if(isset($user->{$this->emailToSetMetadata}) && $user->{$this->emailToSetMetadata} == $emailToSet){
					// \Uoowd\Logger::addError('non mando mail...');
				}else{
					// \Uoowd\Logger::addError('mando mail...');
					// quando mano la mail, imposto questa come metadato da controllare...
					// e' importante rimuoverlo una volta ricevuta la conferma!
					$v['emailToSet'] = $emailToSet;
					$v['guid'] = $user->guid;
					// $v['oldEmail'] = $user->email;
					$this->foowd_change_email_send($v);
					$user->{$this->emailToSetMetadata} = $emailToSet;
					$user->{$this->emailExpiration} = $this->daysToExpire * 24 * 60 + time();
					$user->save();
				}
			}

			
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


	public function foowd_change_email_send($v){
		// $v['emailToSet'] = $emailToSet;
		// $v['guid'] = $user->guid;
		// $v['oldEmail'] = $user->email;
		
		extract($v);

		$toCrypt = 'emailToSet=' . $emailToSet . '&guid=' . $guid;
		// uso lo chiave in maniera mooolto semplice... la sicurezza in questo passaggio e' bassisima, e va bene cosi'
		$encode = urlencode( $this->cryptKey . base64_encode($toCrypt) );

		$link = elgg_get_site_url().'foowd_utenti/emailAction?changeEmail='.$encode;


		$txt='
		Salve,

		dal sito %s un\'utente ha chiesto di aggiornare i suoi dati impostando la presente email come suo indirizzo personale.
		E\' possibile dare conferma cliccando (o copiando e incollando nella barra degli indirizzi) il seguente link:

		    %s

		Se tale richiesta non le risulta avanzata, ci scusiamo per il disguido.

		Cordialmente,
		%s
		';

		$from = elgg_get_config('sitename');
		$to = $emailToSet;
		$body = sprintf($txt, elgg_get_site_url(), $link, $from);
		$subject = 'Richiesta Modifica Email';
		elgg_send_email($from, $to, $subject, $body, array());

	}

	// per questa non serve essere loggati...
	public function foowd_change_email_confirm($url){

		$url = urldecode($url);
		$url = preg_replace('@^'.$this->cryptKey.'@', '', $url);
		$url = base64_decode($url);
		parse_str($url);
		// l'utente a cui cambiare la mail
		$owner = get_entity($guid);
		// l'utente che svolge il cambio.
		$user = elgg_get_logged_in_user_entity();
		// come al solito gli amministratori possono tutto
		// se e' un'amministratore, o l'utente loggato, o l'utente non e' ancora loggato
		if(!$user || $user->isAdmin() || $owner->guid == $user->guid){
			
			// se non era loggato, ora lo loggo: in questo modo assume i privilegi per salvare le modifiche all'utente $owner
			if(!$user) login($owner);

			// Anzitutto aggiorno lati API DB
			$data = array();
			$data['type'] = 'update';
			$data['ExternalId'] = $owner->guid;
			$data['Email'] = $emailToSet;

			$r = \Uoowd\API::userPost($data);
			// \Uoowd\Logger::addError($r);
			
			if(!$r->response){
				register_error('Siamo spiacenti ma abbiamo riscontrato un errore nella registrazione della nuova mail.<br/>Suggeriamo di ripetere l\'operazione modificando il form.');
				forward(\Uoowd\Param::userPath('settings', $owner->guid));
			}

			// la vecchia mail per il nuovo utente
			$oldEmail = $owner->email;
			
			// Aggiorno i nuovi dati
			$owner->email = $emailToSet;
			$owner->{$this->emailExpiration} = '';
			$owner->{$this->emailToSetMetadata} = '';
			$owner->save();

			system_message('Email reimpostata con successo!');

			// invio della mail
			$txt='
			Salve %s,
	
			l\'aggiornamento della tua mail all\'indirizzo %s e\' avvenuto con successo.
	
			Ti ricordiamo che l\'attuale indirizzo mail non sara\' piu\' valido, ma potrai reimpostarlo se lo vorrai.
	
			Cordialmente,
			%s
			';
	
			$from = elgg_get_config('sitename');
			$to = $oldEmail;
			$body = sprintf($txt, $owner->username, $emailToSet, $from);
			$subject = 'Richiesta Modifica Email';
			elgg_send_email($from, $to, $subject, $body, array());		

			forward();

		}


	}


}