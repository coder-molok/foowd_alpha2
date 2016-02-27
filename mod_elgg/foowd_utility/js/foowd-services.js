/**
 * IN GENERALE
 * <pre>
 * 		preferisco scambiare gli username come parametri al posto degli id: questo perche' a mio avviso e' piu sicuro trasferire il nome utente,
 * 		che rimane come parametro pubblico, al posto dell'id, anche se di fatto non cambia molto...
 * </pre>
 *
 * 
 * @module  foowdServices
 * @requires {@link module:pages}
 * @requires elgg
 * @requires jquery
 */


define(function(require){

	var foowdService = (function(){
		/**
		  * oggetto che ritornera' il modulo.
		  * 
		  * @constructor
		  * @alias module:foowdServices
		  */
		var serviceObj = {};

		var page = require('page');
		var $ = require('jquery');
		var elgg = require('elgg');

		/**
		 * classe privata statica
		 * @constructor module:foowdServices._pr
		 *
		 * @property {string} 	servUrl		- url pagina di servizi locale
		 * @property {string}  	elggAPI		- url pagina di Elgg Web Services
		 * @property {function} userGuid	- ottenere userGuid
		 * @property {function} userEntity	- ottenere userEntity
		 */
		var _pr = {
			servUrl : elgg.get_site_url()+page.services,
			/**
			 * path completo per le API Elgg Web Services
			 * 
			 */
			elggAPI : elgg.get_site_url()+page.elggAPI,
			/** ottengo l'id dell'utente loggato */
			userGuid : function(){ return elgg.get_logged_in_user_guid(); },
			/** ottengo l'entia' dell'utente loggato */
			userEntity : function(){ return elgg.get_logged_in_user_entity(); },
			
		}
		/** @property {mixed} test ciao */
		_pr.test = 'lol'


		/**
		 * Ricordando che le relationship non sono bidirezionali,
		 * la domanda a cui risponde questa funzione e':
		 *  guid1 e' amico di guid2?
		 *
		 * se guid1 == guid2 allora non sono in relazione friend (io non sono amico di me stesso), pertanto avrei false...
		 * 
		 */
		serviceObj.currentIsFriendOf = function(username){
			var guid1 = username;
			var guid2 = elgg.get_logged_in_user_entity().username;
			var relationship = 'friend';
			var myUrl = _pr.servUrl //+'?subject=' + guid1 + '&verb=' + relationship +'&target=' + guid2
			var data = {
				subject: guid1,
				verb: relationship,
				target: guid2
			}
			
			return $.ajax({ type : 'POST',	url: myUrl,	'data': data });
		}

		/**
		 * prende l'attuale url riportando la querystring in forma di oggetto
		 * @alias module:foowdServices.urlPar
		 * @private
		 */
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
		serviceObj.getIdToBoard = function(){

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


		/**
		 * prende un oggetto e trasforma tutte le poprieta:valori in "&prop=value"
		 * @alias module:foowdServices.getUrl
		 * @private
		 */
		var getUrl = function(obj){
			var str = _pr.elggAPI + obj.method;
			for(prop in obj.get) str += '&'+prop+'='+obj.get[prop];
			return str;
		}

		
		/** ritorna la chiamata ajax */
		serviceObj.getPicture = function(obj){
			data = {'method' : 'foowd.picture.get', 'get': obj }
			// filtro la risposta in modo che venga ritornata secondo le convenzioni del web service
			return $.ajax({ type : 'GET',	'url': getUrl(data) }).then(function(data){
				var obj ={
					'status' : 0 ,
					'result' : {'picture' : data}
				}
				return obj;
			});
		}

		/** ritorna l'url src da inserire nel tag <img> */
		serviceObj.getPictureUrl = function(obj){
			data = {'method' : 'foowd.picture.get', 'get': obj }
			var obj ={ 'status': 0, 'result': {'picture': getUrl(data)} };
			return obj;
		}

		/**
		 * ritorna un oggetto $.ajax, la cui risposta 
		 * e' un oggetto contenente userId e friends (array di id degli amici) : id ELGG
		 *
		 * parametri: 
		 *
		 * 	- nessuno: ottiene i dati dell'utente loggato
		 * 	- stringa id : ottiene i dati dell'utente di cui id specificato
		 *
		 * 
		 * @param  {mixed} userId  stringa o numero contenente id
		 * @return {deferred}        deferred
		 */
		serviceObj.getFriendsOf =  function( userId ){
			userId = (typeof userId === 'undefined') ? _pr.userGuid() : userId ;
			if(userId){
				var dt = {'method': 'foowd.user.friendsOf'}
				var dat = {'guid': userId} ;
				var $ajax = $.ajax({ type: 'POST', 'url': getUrl(dt) , data: dat });
			}else{
				var $ajax ={'result': {'response' : false, 'msg' : 'Impossibile ottenere userId'} };
			}

			return $ajax;
		}


		/**
		 * ritorna un oggetto $.ajax, la cui risposta 
		 * e' un oggetto contenente la risposta a Api Purchase Solve lato API DB.
		 *
		 * parametri: 
		 *
		 * 	- nessuno: ottiene i dati dell'utente loggato
		 * 	- stringa id : ottiene i dati dell'utente di cui id specificato
		 *
		 * 
		 * @param  {mixed} PurchaseId  stringa contenente uno o piu id separati da virgola
		 * @return {deferred}        deferred
		 */
		serviceObj.purchaseSolve =  function( PurchaseId ){
			userId = (typeof userId === 'undefined') ? _pr.userGuid() : userId ;
			if(userId){
				var dt = {'method': 'foowd.admin.purchaseSolve'}
				var dat = {'PurchaseId': PurchaseId} ;
				var $ajax = $.ajax({ type: 'POST', 'url': getUrl(dt) , data: dat });
			}else{
				var $ajax ={'result': {'response' : false, 'msg' : 'Impossibile ottenere userId'} };
			}

			return $ajax;
		}

		serviceObj.getRequest =  function( obj ){
			var dt = {'method': obj.method}
			delete obj.method;
			dt.get = obj;
			var $ajax = $.ajax({ type: 'GET', 'url': getUrl(dt), cache: false });
			return $ajax;
		}




		return serviceObj ;

	})()


	return foowdService;


});