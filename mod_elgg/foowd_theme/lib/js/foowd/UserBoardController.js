define(function(require) {

	var API = require('FoowdAPI');
	var Navbar = require('NavbarController');
	var templates = require('templates');
	var utils = require('Utils');
	var $ = require('jquery');
	var service = require('foowdServices');

	var UserBoardController = (function(){

		var preferencesContainerId =  "#preferences-container";
		var userDetailsContainer = "#account-menu";
		//userId reference
   		var userId = null;
   		/* SS: parametro aggiunto ipotizzando che la board possa essere visualizzata anche da amici */
   		var userName = null;
   		//preferenza utente
   		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "",
   		};

   		//nel controller devo essere sicuro che il dom sia stato casricato correttamente
		function _stateCheck(){
			switch(document.readyState){
				case "loading":
					document.onreadystatechange = function (){
						_stateCheck();
					}
				break;
				case "interactive":
				case "complete": 
					_init();
				break;
			}
		}
   		//controller init
   		function _init(){
   			/* SS: service.getIdToBoard() controlla la querystring per capire se al wall sta accedendo il proprietario o un amico:
   				se tutto va a buon fine "us.guid" e' l'id dell'utente del quale si vuole visualizzare il wall, e "us.userName" il suo username.
   				Nel caso non vada a buon fine, i valori sono null.

   				NB: attenzione ai pulsanti: in teoria quelli non dovrebbero essere visualizzabili: forse cliccandoci sopra si rischia di esprimere le preferenze 
   				come se si fosse nei panni del possessore della board: rivedere questa azione.
   			*/
   			$.when(service.getIdToBoard()).done(function(us){
   				//prendo lo user id
   				userId = us.guid;
   				userName = us.userName;
	   			//carico la barra di navigazione
	   			Navbar.loadNavbar();
	   			//carico i template
	   			_getUserPreferences();
	   			_getUserInfo();			
   			});
   		}

   		function _getUserPreferences(){
			API.getUserPreferences(userId).then(function(data){
				var rawData = data;
				var parsedProducts = _applyPreferencesContext(rawData.body);
				_fillBoard(parsedProducts);
				$(document).trigger('preferences-loaded');
			},function(e){
				console.log(e);
			});

		}

   		function _applyPreferencesContext(context) {
			var result = "";
			context.map(function(el) {
				//aggiungo l'immagine al json di contesto
				utils.addPicture(el.Offer, 'small');
				//ottengo l'html dal template + contesto
				var htmlComponent = templates.userPreference(el);
				//concateno
				result += htmlComponent;
			});

			return result;
		}
		function _fillBoard(content) {
			$(preferencesContainerId).html(content);
		}

		function _getUserInfo(){
			API.getUserPics(userId).then(function(data){
				var user = {};
				user.avatar = utils.isValid(data.avatar) ? data.avatar[3] : null;
				var parsedProducts = _applyUserContext(user);
				_fillUserDetails(parsedProducts);
			}, function(error){
				console.log(error);
			});
		}

		function _applyUserContext(avatar){
			var context = {};
			context.user = {
				"name"  : userName,
				"Publisher" : userId,
				"likes" : $('.preference').length,
			}
			context.user = utils.addProfilePicture(context.user,avatar);
			return templates.preferenceAccountDetails(context);
		}

		function _fillUserDetails(content){
			$(userDetailsContainer).html(content);
		}

		function _fillProgressBars(){
			$('.progress').each(function(i) {
			    var unit = $(this).data('unit');
			    var progress = $(this).data('progress');
			    var total = $(this).data('total');

			    var width = (progress/total)*100;
			    width = width > 100 ? 100 : width;
			    
			    $(this).width(width + "%");
			});
		}

		function _addPreference(offerId, qt) {
    		//setto i parametri della mia preferenza
			preference.OfferId = offerId;
			preference.ExternalId = userId;
			preference.Qt = qt;
			//richiamo l'API per settare la preferenza
			API.addPreference(preference).then(function(data){
				$(document).trigger('preferenceAdded');
			}, function(error){
				$(document).trigger('preferenceError');
				console.log(error);
			});

		}

		$(document).on('preferences-loaded', function(){
			_fillProgressBars();
		});

		$(document).on('preferenceAdded', function(){
			_getUserPreferences();
			_getUserInfo();
		});

		return{
			init 		  : _stateCheck, 
			addPreference : _addPreference,
		};

	})();

	return UserBoardController;
});