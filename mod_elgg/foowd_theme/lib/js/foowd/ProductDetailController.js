define(function(require){

	//API foowd
	var API = require('FoowdAPI');
	//templates handlebars
	var templates = require('templates');
	//elgg utility
	var elgg = require('elgg');
	//jQuery
	var $ = require('jquery');
	//util library
	var utils = require('Utils');
	//creo il controller della pagina dettaglio
	var ProductDetailController = (function(){

		//id html del contenitore dei dettagli prodotto
		var productContainer = '#product-detail-main';
		
		//userId reference
   		var userId = null;

   		//preferenza utente
   		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "",
   		};

   		//inizializzo il controller
   		function _init(){
   			//prendo lo user id
   			userId = elgg.get_logged_in_user_guid() === 0 ? null : elgg.get_logged_in_user_guid();
   			//carico il template del prodotto con i dati
   			getDetailsOf();
   			//carico la barra di navigazione
   			utils.loadNavbar();
   		}

   		function _fillProductDetail(content){
			$(productContainer).html(content);
		}

		function _applyProductContext(context){
			utils.addPicture(context);
			utils.setLoggedFlag(context, userId);
			return templates.productDetail(context);
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
			//prendo l'id del prodotto dall'url
			var queryUrl = elgg.parse_url(window.location.href).query;
			if(utils.isValid(queryUrl)){
				//splitto i vari parametri dell'url
				var sURLVariables = queryUrl.split('&');
				//creo l'oggeto finale
				var queryObject = {};
				//aggiungo i parametri all'oggetto
				for(var i = 0; i < sURLVariables.length ; i++){
				  var args = sURLVariables[i].split('=');
				  queryObject[args[0]] = args[1];
				}
				//controllo che tra i parametri ci sia l'id del prodotto
				if(queryObject.productId){
					//richiamo la API per i dettagli del prodotto
					API.getProduct(queryObject.productId).then(function(data){
						//parso in JSON il risultato
						var rawProduct = $.parseJSON(data).body[0];
						var parsedProduct = _applyProductContext(rawProduct);
						_fillProductDetail(parsedProduct);

						$(document).trigger('detail-template-loaded');

					}, function(error){
						//gestico l'errore
						console.log(error);
					});
				}else{
					//reindirizzo alla pagina del wall
				  	elgg.forward("/");
				}
			}else{
				//reindirizzo alla pagina del wall
				elgg.forward("/");
			}
		};

		function addPreference(offerId, qt) {
    		//setto i parametri della mia preferenza
			preference.OfferId = offerId;
			preference.ExternalId = userId;
			preference.Qt = qt;
			//richiamo l'API per settare la preferenza
			API.addPreference(preference).then(function(data){
				getDetailsOf(productContainer);
				$(document).trigger('preferenceAdded');
			}, function(error){
				$(document).trigger('preferenceError');
				console.log(error);
			});

		}

		$(document).ready(function(){
			_init();
		});

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
			getDetailsOf : getDetailsOf,
			addPreference : addPreference
		};
	})();

	return ProductDetailController;
});