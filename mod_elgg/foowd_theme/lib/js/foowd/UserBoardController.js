define(function(require) {

	//foowd api
	var API = require('FoowdAPI');
	//templates pre compilati
	var templates = require('templates');
	//libreria di utility
	var utils = require('Utils');
	//jQuery 
	var $ = require('jquery');

	var UserBoardController = (function(){

		var preferencesContainerId =  "#preferences-container";
		var userDetailsContainer = "#account-menu";
		//userId reference
   		var userId = null;
   		//preferenza utente
   		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "",
   		};

   		//controller init
   		function _init(){
   			//prendo lo user id
   			userId = elgg.get_logged_in_user_guid() === 0 ? null : elgg.get_logged_in_user_guid();
   			//carico la barra di navigazione
   			utils.loadNavbar();
   			//carico i template
   			getUserPreferences();
   		}

   		function _applyProductContext(context) {
			var result = "";
			context.map(function(el) {
				//aggiungo l'immagine al json di contesto
				utils.addPicture(el.Offer);
				//ottengo l'html dal template + contesto
				var htmlComponent = templates.userPreference(el);
				//concateno
				result += htmlComponent;
			});

			return result;
		}

		function _applyUserContext(preferenceData){
			var context = {};
			context.user = {
				"name" : elgg.get_logged_in_user_entity().name,
				"likes" : preferenceData.length,
			}

			return templates.preferenceAccountDetails(context);
		}
		
		function _fillBoard(content) {
			$(preferencesContainerId).html(content);
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

		function getUserPreferences(){
			API.getUserPreferences(userId).then(function(data){
				
				var rawData = $.parseJSON(data);
				var parsedProducts = _applyProductContext(rawData.body);
				var parsedUserData = _applyUserContext(rawData.body);
				_fillBoard(parsedProducts);
				_fillUserDetails(parsedUserData);

				$(document).trigger('preferences-loaded');
			},function(e){
				console.log(e);
			});

		}

		function addPreference(offerId, qt) {
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

		$(document).ready(function(){
			_init();
		});

		$(document).on('preferences-loaded', function(){
			_fillProgressBars();
		});

		$(document).on('preferenceAdded', function(){
			getUserPreferences();
		});

		return{
			addPreference : addPreference
		};

	})();

	return UserBoardController;
});