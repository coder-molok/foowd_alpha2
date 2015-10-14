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
		"massa": {"kilogrammi":"kg","grammi":"g"},
		"volume": {"litri":"l","centilitri":"cl"}
		// "formati": {"bicchieri":"bicchieri","bottiglie":"bottiglie","forme":"forme"}
	}
);