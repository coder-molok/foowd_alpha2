<?php

/**
 * Pagina creata per servire l'elenco di pagine->url in due formati:
 *
 * - default: PHP
 * 		se questa pagina viene richiesta tramite require(), allora basta utilizzarla col comando
 * 		$pages = require(<this page url>);
 * 		e pages sara' l'oggetto php ivi creato
 * 		
 * - ?type=amd
 * 		se questa pagina viene reperita mediante una richiesta get alla medesima ma con estensione ".js", allora il file .htaccess provvede automaticamente a includerla come ".js" e in query string appende "type=amd", e con questo parametro viene restituito un semplice modulo AMD , ovvero un formato json dell'oggetto, dentro a un define;
 */


$p = new stdClass();


$p->all 			= "foowd_offerte/all"; 					// dove l'utente visualizza le offerte che ha creato/pubblicato
$p->add 			= "foowd_offerte/add"; 					// form per la creazione di una nuova offerta
$p->success			= "foowd_offerte/success"; 				/* pagina di redirect in caso di salvataggio andato a buon fine */
$p->single 			= "foowd_offerte/single"; 				// visualizzazione singola offerta
$p->userPreferences = "foowd_utenti/my-preferences"; 		// dove l'utente visualizza l'elenco delle sue preferenze ed il match con gli amici
$p->profile			= "profile";
$p->foowdStorage 	= "../FoowdStorage/"; 					// directory contenente le immagini delle offerte
$p->auth 			= "foowd_utenti/auth"; 					// pagina utilizzata per il login mediante socials
$p->indexauth 		= "foowd_utenti/indexauth"; 			// pagina utilizzata per il login mediante socials
$p->profile 		= "foowd_utenti/profilo"; 				// pagina del profilo
$p->social 			= "foowd_utenti/social"; 				// pagina social: primi test sulle OAuth apps
$p->legalConditions	= "foowd_utenti/legal"; 				// pagina condizioni legali
$p->cookiePolicy	= "cookie-policy"; 						// pagina cookie policy
$p->panel 			= "panel"; 					 			// pagina del pannello
$p->purchase 		= "foowd_utenti/purchase"; 				// pagina contenente l'elenco degli ordini ancora da chiudere
$p->suggestedTags 	= "foowd_utenti/suggestedTags"; 		// pagina per visualizzazione tags suggeriti
$p->evaluatingUsers = "foowd_utenti/evaluatingUsers"; 		// visualizza gli utenti offerenti da approvare
$p->friendsManage 	= "friend_request"; 					// pagina del plugin friend request
$p->services 		= "foowd_utility/services"; 			// pagina servizi che comunicano con foowd-services.js
$p->elggAPI 		= "services/api/rest/json/?method="; 	// pagina API REST di foowd
$p->guide 			= "http://www.tiny.cc/guida_foowd_1"; 	// pagina guida

// action
$pa = new stdClass();
$pa->initPurchase 	= "foowd-purchase-leader";				// action per far partire l'ordine
$pa->suggestedTags = "foowd-suggested-tags";				// action per gestione tags da pannello di controllo
$p->action = $pa;



if(isset($_GET['type'])){
	switch ($_GET['type']){
		case 'amd':
			echo ";define(" . json_encode($p) . ");";
	}
}
else{

	$foowdPages = $p;

	return $foowdPages;

}

