define(function(require) {

	var API = require('FoowdAPI');
	var Navbar = require('NavbarController');
	var templates = require('templates');
	var utils = require('Utils');
	var $ = require('jquery');
	var service = require('foowdServices');
	var elgg = require('elgg');

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
	   			// impostati i deferred per garantire che _getUserInfo() si realizzi in cascata
	   			// al fine di risolvere l'errore di conteggio delle preferenze
	   			_getUserPreferences().then(function(){
	   				_getUserInfo();		
	   			});
   		}

   		function _getUserPreferences(){
			var userId = utils.getUserId();
			if(userId!=null && group){
				return _getUserPreferencesGroup(userId);
			}else{
				return _getUserPreferencesSingle(userId);
			}

		}
		
		function _getUserPreferencesSingle(userId){
			return API.getUserPreferences(userId).then(function(data){
				var rawData = data;
				// console.log(data)
				var parsedProducts = _applyPreferencesContext(rawData.body);
				_fillBoard(parsedProducts);
				$(document).trigger('preferences-loaded');
				_applyColor();
			},function(e){
				console.log(e);
			});

		}
		function _getUserPreferencesGroup(userId){
			return API.getFriend(userId).then(function(data){
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
				el.Qt = el.totalQt;
				el.Offer.prefers = el.prefers.join(',');
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
				"likes" : $('.user-preference').length,
			}
			context.user = utils.addProfilePicture(context.user,avatar);
			return templates.preferenceAccountDetails(context);
		}
		
		function _applyColor(){

				$( ".progress" ).each(function() {
					$(this).toggleClass('action-heart',!group);
					$(this).toggleClass('action-minus',group);

				});
				$( ".btn-buy" ).each(function() {
					$(this).toggleClass('action-buy-border',!group);
					$(this).toggleClass('action-buy-group',group);
				});
				$( ".btn-buy-icon" ).each(function() {
					$(this).toggleClass('foowd-icon-cart-white',group);
					$(this).toggleClass('foowd-icon-cart',!group);
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
				_getUserPreferences();
				_getUserInfo();
			}, function(error){
				$(document).trigger('preferenceError');
				console.log(error);
			});

		}

		$(document).on('preferences-loaded', function(){
			_fillProgressBars();
		});

		function toggleGroup(){
						group=!group;
			$('#groupBtn').toggleClass('foowd-icon-group-white',group);
			$('#groupBtn').toggleClass('foowd-icon-group',!group);
			$('#groupBtn').toggleClass('fw-menu-icon-group',group);
			$('#groupBtn').toggleClass('fw-menu-icon',!group);
			//Lo applico anche prima che carichi
			_applyColor();
			_getUserPreferences();
		}


		$(document).on('click', 'li.btn-buy', function(e){

			var offerId = $(this).attr('data-offer-id');
			var prefers = $(this).attr('data-offer-prefers');

			// ottengo il contenitore e gli appendo una classe che inibisce i pulsanti(una maschera trasparende che lo ricopre e lo blocca)
			var $box = 	$(this).closest('.preference');
			$box.addClass('preference-lock');

			API.purchase(offerId,utils.getUserId(),prefers).then(function(data){
				if(typeof data.output.errors != 'undefined') return;
				elgg.system_message("L'ordine è stato preso in carico,<br/> ti stiamo inviando una mail con i dettagli.");
				// effetto di dissolvenza
				$box.addClass('preference-fadeOut');
				// lo rimuovo
				$box.remove();
				var prefs = $('.user-preference').length;
				$('#account-info').find('li').first().html(prefs);
				// aggiorno il conteggio dei prodotti
			}, function(error){
				// se avviene un errore, posso comunque riprendere ad aggiungere preferenze
				$box.removeClass('preference-lock');
				$(document).trigger('preferenceError');
				console.log(error);
			});
		});
	
		
	   	window.toggleGroup = toggleGroup;

		return{
			init 		  : _stateCheck, 
			addPreference : _addPreference,
		};

	})();

	return UserBoardController;
});