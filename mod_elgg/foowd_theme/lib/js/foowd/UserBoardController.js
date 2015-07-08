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
			fillProgressBars();
		}

		function fillProgressBars(){
			$('.progress-bar').each(function(i) {
			    var width = $(this).data('width');
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
			},function(e){
				console.log(e);
			});

		}

		return{
			getUserPreferences : getUserPreferences
		};

	})();

	return UserBoardController;
});