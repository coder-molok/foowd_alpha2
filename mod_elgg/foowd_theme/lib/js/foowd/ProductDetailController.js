define(function(require){

	var API = require('FoowdAPI');
	var Navbar = require('NavbarController');
	var templates = require('templates');
	var $ = require('jquery');
	var utils = require('Utils');
	var elgg = require('elgg');

	var ProductDetailController = (function(){

		//id html del contenitore dei dettagli prodotto
		var productContainer = '#product-detail-main';
		
		//userId reference
		var group = false;

   		//preferenza utente
   		var preference = {
   				OfferId : "",
   				type : "create",
   				Qt : "",
   		};

   		//controllo dello stato
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

   		//inizializzo il controller
   		function _init(){
   			//load navbar
   			Navbar.loadNavbar();
   			_applyColor();
   			//carico il template del prodotto con i dati
   			getDetailsOf();
   		}

   		function _fillProductDetail(content){
			$(productContainer).html(content);
		}

		function _applyProductContext(context){
			context = utils.offerPrepare(context, group);
			// context = utils.addPicture(context);
			// context = utils.setLoggedFlag(context, utils.getUserId());
			// context.offer = utils.setLoggedGroup(context.offer, group);
			return templates.productDetail(context.offer);
		}

		function _fillProgressBars(){
			$('.progress').each(function(i){
			    var unit = $(this).data('unit');
			    var progress = $(this).data('progress');
			    var total = $(this).data('total');

			    var width = (progress/total)*100;
			    width = width > 100 ? 100 : width;
			    $(this).width(width + "%");
			});
		}

		function getDetailsOf(){
			var userId=utils.getUserId();
			if(group){
				getDetailsOfGroup(''/*userId*/);
			}else{
				getDetailsSingle(''/*userId*/);
			}
		
		}


		function getDetailsOfGroup(userId){
			// API.getFriend(userId).then(function(data){
			// 	var friendsStr='';
			// 	if(data.result && data.result.friends){
			// 		 friendsStr = data.result.friends.join();
			// 	}
				getDetailsSingle('&withFriends=true');
			// },function(error){
			// 		console.log(error);
			// });
		}


		function getDetailsSingle(userId){
			var queryObject = utils.getUrlArgs();
			//controllo che tra i parametri ci sia l'id del prodotto
			if(utils.isValid(queryObject.productId)){
				//richiamo la API per i dettagli del prodotto
				API.getProduct(queryObject.productId+userId+'&forCurrentUser=true', userId).then(function(data){
					//parso in JSON il risultato
					var rawProduct = data.body[0];
					var parsedProduct = _applyProductContext(rawProduct);
					_fillProductDetail(parsedProduct);
					_applyColor();
					$(document).trigger('detail-template-loaded');

				}, function(error){
					//gestico l'errore
					console.log(error);
				});
				_applyColor();
			}else{
				//reindirizzo alla pagina del wall
				alert('lol')
			 	utils.goTo();
			}
		};

		function addPreference(offerId, qt) {
    		//setto i parametri della mia preferenza
			preference.OfferId = offerId;
			preference.Qt = qt;
			//richiamo l'API per settare la preferenza
			API.addPreference(preference).then(function(data){
				getDetailsOf();
				if(typeof data.errors != 'undefined'){
					if(typeof data.errors.Expiration != 'undefined') $(document).trigger({type: 'popupError', foowdMSG : 'Offerta scaduta.'});
					if(typeof data.errors.blockedPref != 'undefined') $(document).trigger({type: 'popupError', foowdMSG : "Offerta gia' presa in carico."});
				}
				//$(document).trigger('preferenceAdded');
			}, function(error){
				$(document).trigger('preferenceError');
				console.log(error);
			});
		}

		function purchase(offerId, prefers) {
			// attivo il cursore
			$('#product-detail-main').toggleClass('progress', true);
    		//setto i parametri della mia preferenza
			//richiamo l'API per settare la preferenza
			API.purchase(offerId,utils.getUserId(),prefers).then(function(data){
				getDetailsOf();
				if(typeof data.output.errors != 'undefined') return;
				elgg.system_message("Ordine effettuato con successo!<br/>A breve riceverai una mail riepilogativa.");
			}, function(error){
				$(document).trigger('preferenceError');
				console.log(error);
			}).always(function(){
				$('#product-detail-main').toggleClass('progress', false);
			});

		}

		function _applyColor(){
				$( ".progress" ).each(function() {
					$(this).toggleClass('action-heart',!group);
					$(this).toggleClass('action-minus',group);

				});
				$( "#action-buy" ).each(function() {
					$(this).toggleClass('action-buy-group',group);
					$(this).toggleClass('action-buy',!group);

				});
				$('#action-buy-icon').toggleClass('foowd-icon-cart-white',group);
				$('#action-buy-icon').toggleClass('foowd-icon-cart',!group); 
				
				
		}
		
		function toggleGroup(){
						group=!group;
			$('#groupBtn').toggleClass('foowd-icon-group-white',group);
			$('#groupBtn').toggleClass('foowd-icon-group',!group);
						$('#groupBtn').toggleClass('fw-menu-icon-group',group);
			$('#groupBtn').toggleClass('fw-menu-icon',!group);

			
			_applyColor();
			getDetailsOf();
		}

		window.toggleGroup=toggleGroup;


		$(document).on('detail-template-loaded',function(){

			_fillProgressBars();
			
			$('#action-heart').mouseenter(function(){
				var bar = $('.preview-bar').find('.progress');
				var unit = bar.data('unit');
			    var progress = bar.data('progress');
			    var total = bar.data('total');

			    var newWidth = ((progress + unit)/total)*100;

			    newWidth = newWidth > 100 ? 100 : newWidth;
			    bar.animate({
			    	width : newWidth + "%"
			    }, 200);

			});

			$('#action-heart').mouseleave(function(){
				var bar = $('.preview-bar').find('.progress');
				
				var unit = bar.data('unit');
			    var progress = bar.data('progress');
			    var total = bar.data('total');

			    var newWidth = ((progress)/total)*100;

			    newWidth = newWidth > 100 ? 100 : newWidth;
			    bar.animate({
			    	width : newWidth + "%"
			    }, 200);

			});
			
			
		});


		return{
			init 			: _stateCheck,
			addPreference   : addPreference,
			purchase   : purchase
		};
	})();

	return ProductDetailController;
});

