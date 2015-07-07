define(function(require) {

	//foowd api
	var API = require('FoowdAPI');
	//templates pre compilati
	var templates = require('templates');
	//libreria di utility
	var utils = require('Utils');
	//jQuery 
	var $ = require('jquery');

	var page = require('page');

	var UserBoardController = (function(){

		var preferencesContainerId =  "#preferences-container";
		//userId reference
   		var userId = elgg.get_logged_in_user_guid() === 0 ? null : elgg.get_logged_in_user_guid();

   		function applyProductContext(context, myTemplate) {
			var result = "";
			context.map(function(el) {
				console.log(el);
				_addPicture(el.Offer);
				result += myTemplate(el);
			});

			return result;
		}
		
		function _addPicture(of) {
            of.picture = page.offerFolder + '/User-' + userId + '/' + of.Id + '/medium/' + of.Id + '.jpg';
        }

		function fillBoard(content) {
			$(preferencesContainerId).html(content);
		}

		function getUserPreferences(){

			API.getUserPreferences(userId).then(function(data){
				var rawProducts = $.parseJSON(data);
				var parsedProducts = applyProductContext(rawProducts.body, templates.userPreference);
				fillBoard(parsedProducts);
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