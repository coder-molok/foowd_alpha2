/**
 * Questo file e' di tipo AMD.
 * In ogni caso sara' letto anche via php mediante il metodo
 *
 * 		\Uoowd\Param::page(<nome_pagine>);
 *
 * affinche' cio' avvenga e' necessario che:
 * 	
 * 	- il "define(" sia posto all'inizio della riga senza essere seguito da nulla
 * 	- la chiusura del define ");" sia sull'ultima riga del file
 *
 * Vedere l'implementazione del metodo sopra citato per eventuali dubbi.
 * 
 */
define(
	{
		"all" : "foowd_offerte/all",  		// dove l'utente visualizza le offerte che ha creato/pubblicato
		"add" : "foowd_offerte/add",  		// form per la creazione di una nuova offerta
		"success": "foowd_offerte/success", /* pagina di redirect in caso di salvataggio andato a buon fine */
		"single": "foowd_offerte/single",	// visualizzazione singola offerta
		"profile": "profile",
		"offerFolder": "../OfferImg/", 		// directory contenente le immagini delle offerte
		"auth": "foowd_utenti/auth" 		// pagina utilizzata per il login mediante socials
	}
);