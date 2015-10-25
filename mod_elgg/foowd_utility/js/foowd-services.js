/**
 * IN GENERALE
 *
 * 		preferisco scambiare gli username come parametri al posto degli id: questo perche' a mio avviso e' piu sicuro trasferire il nome utente,
 * 		che rimane come parametro pubblico, al posto dell'id, anche se di fatto non cambia molto...
 */


define(function(require){

	var foowdService = (function(){

		var page = require('page');
		var $ = require('jquery');
		var elgg = require('elgg');

		// variabile privata
		var servUrl = elgg.get_site_url()+page.services;

		/**
		 * Ricordando che le relationship non sono bidirezionali,
		 * la domanda a cui risponde questa funzione e':
		 *  guid1 e' amico di guid2?
		 *
		 * se guid1 == guid2 allora non sono in relazione friend (io non sono amico di me stesso), pertanto avrei false...
		 * 
		 */
		var currentIsFriendOf = function(username){
			var guid1 = username;
			var guid2 = elgg.get_logged_in_user_entity().username;
			var relationship = 'friend';
			var myUrl = servUrl //+'?subject=' + guid1 + '&verb=' + relationship +'&target=' + guid2
			var data = {
				subject: guid1,
				verb: relationship,
				target: guid2
			}
			
			return $.ajax({ type : 'POST',	url: myUrl,	'data': data });
		}

		var urlPar = (function(){
			var match,
			pl     = /\+/g,  // Regex for replacing addition symbol with a space
			search = /([^&=]+)=?([^&]*)/g,
			decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
			query  = window.location.search.substring(1);
			urlParams = {};
			while (match = search.exec(query))
			urlParams[decode(match[1])] = decode(match[2]);
			return urlParams;
		})();

		/**
		 * Controlla se nella querystring e' settato un "owner", che sarebbe il possessore della board (l'utente attuale potrebbe essere semplicemente un amico)
		 * Se "owner" e' impostato, allora la callback del then() ritorna l'id dell'utente, altrimenti null (per coerenza con quanto fatto da Marco P.)
		 * 
		 * In assoluto la funzione ritorna un deferred: false se non sono amici, altrimenti l'id della board da caricare
		 * 
		 */
		var getIdToBoard = function(){

			var username = urlPar.owner || false
			var user = {}

			/* 	il false e' stato introdotto per impedire la visualizzazione della board di altri utenti, anche se amici.
				Tengo questo codice perche' reputo alta la probabilita' di doverlo reintrodurre nelle prossime fasi */
			if(false && username){
				// ritorno il deferred
				return currentIsFriendOf(username).then(function(data){
					 user.guid = (data.relationship) ? data.subject : null ;
					 user.userName = username;
					 return user;
				})
			}
			else{
				user.guid = elgg.get_logged_in_user_entity().guid === 0 ? null : elgg.get_logged_in_user_guid();
				user.userName = elgg.get_logged_in_user_entity().username || null;
				return user;
			}

		}



		// serv.currentIsFriendOf(109).then(function(data){ 
		// 	console.log(data);
		// });


		return{
			'currentIsFriendOf' : currentIsFriendOf,
			'getIdToBoard' : getIdToBoard
		}

	})()


	return foowdService;


});