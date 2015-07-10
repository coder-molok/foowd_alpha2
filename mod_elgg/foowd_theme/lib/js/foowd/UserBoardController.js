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
		//userId reference
   		var userId = elgg.get_logged_in_user_guid() === 0 ? null : elgg.get_logged_in_user_guid();

   		//preferenza utente
   		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "",
   		};

   		function applyProductContext(context, myTemplate) {
			var result = "";
			context.map(function(el) {
				//aggiungo l'immagine al json di contesto
				utils.addPicture(el.Offer);
				//ottengo l'html dal template + contesto
				var htmlComponent = myTemplate(el);
				//concateno
				result += htmlComponent;
			});

			return result;
		}
		
		function fillBoard(content) {
			$(preferencesContainerId).html(content);
		}

		function fillProgressBars(){
			$('.progress').each(function(i) {
			    var unit = $(this).data('unit');
			    var progress = $(this).data('progress');
			    var total = $(this).data('total');

			    var width = (progress/total)*100;
			    width = width > 100 ? 100 : width;
			    
			    $(this).width(width + "%");
			});
		}

		function setUserNameLabel(){
			$('#username').html(elgg.get_logged_in_user_entity().name);
		}

		function getUserPreferences(){
			API.getUserPreferences(userId).then(function(data){
				var rawProducts = $.parseJSON(data);
				var parsedProducts = applyProductContext(rawProducts.body, templates.userPreference);
				fillBoard(parsedProducts);
				setUserNameLabel();
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
			getUserPreferences();
		});

		$(document).on('preferences-loaded', function(){
			fillProgressBars();
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