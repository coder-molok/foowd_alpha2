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
   		var group = false;

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
				Navbar.loadNavbar(true);
	   			//carico i template
	   			group=false;
	   			_applyColor();
	   			_getUserPreferences();
	   			_getUserInfo();		
   		}

   		function _getUserPreferences(){
			var userId = utils.getUserId();
			if(userId!=null && group){
				_getUserPreferencesGroup(userId);
			}else{
				_getUserPreferencesSingle(userId);
			}

		}
		
		function _getUserPreferencesSingle(userId){
			API.getUserPreferences(userId).then(function(data){
				var rawData = data;
				var parsedProducts = _applyPreferencesContext(rawData.body);
				_fillBoard(parsedProducts);
				$(document).trigger('preferences-loaded');
				_applyColor();
			},function(e){
				console.log(e);
			});

		}
		function _getUserPreferencesGroup(userId){
			API.getFriend(userId).then(function(data){
				var friendsStr='';
				if(data.result && data.result.friends){
					 friendsStr = data.result.friends.join();
				}
				_getUserPreferencesSingle(userId+','+friendsStr);
			},function(error){
					console.log(error);
			});

		}

   		function _applyPreferencesContext(context) {
			var result = "";
			context.map(function(el) {
				//aggiungo l'immagine al json di contesto
				utils.addPicture(el.Offer, 'small');
				utils.setLoggedGroup(el, group);

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
			var userId = utils.getUserId();
			API.getUserPics(userId).then(function(data){
				var user = {};
				user.avatar = utils.isValid(data.avatar) ? data.avatar[3] : null;
				var parsedProducts = _applyUserContext(user,userId);
				_fillUserDetails(parsedProducts);
			}, function(error){
				console.log(error);
			});
		}

		function _applyUserContext(avatar,userId){
			var context = {};
			context.user = {
				"name"  : userName,
				"Publisher" : userId,
				"likes" : $('.preference').length,
			}
			context.user = utils.addProfilePicture(context.user,avatar);
			return templates.preferenceAccountDetails(context);
		}
		
		function _applyColor(){

				$( "#logo" ).each(function() {
					$(this).toggleClass('logo-green',group);
					$(this).toggleClass('logo',!group);

				});
				$( ".progress" ).each(function() {
					$(this).toggleClass('logo',!group);
					$(this).toggleClass('logo-green',group);

				});
			
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
						var userId = utils.getUserId();

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
		function toggleGroup(){
			$('#groupBtn').toggleClass('foowd-icon-user foowd-icon-heart-edge');
			group=!group;
			//Lo applico anche prima che carichi
			_applyColor();
			_getUserPreferences();
		}
		
		
	   	window.toggleGroup = toggleGroup;

		return{
			init 		  : _stateCheck, 
			addPreference : _addPreference,
		};

	})();

	return UserBoardController;
});