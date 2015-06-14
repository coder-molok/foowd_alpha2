define(function(require){

	//foowd api
	var API = require('FoowdAPI');
	//templates pre compilati
	var templates = require('templates');
	//libreria di utility
	var utils = require('Utils');
	//informazioni sulla pagina
	var page = require('page');
	//jQuery 
	var $ = require('jquery');
	//isotope per il layout degli oggetti
	var Isotope = require('isotope');


	var WallController = (function(){

		//tag html dove andiamo a mettere il template compilato
		var wallId = ".wall";
		//search box id
		var searchBox = "#searchText";
		//prototipo di una prefereza
		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "0",
   		};
   		//userId reference
   		var userId = elgg.get_logged_in_user_guid() === 0 ? null : elgg.get_logged_in_user_guid();
		/*
		 * Funzione che riempe il tag html con i template dei prodotti complilati
		 */
		function fillWall(content) {
			$(wallId)
		  	    .html(content)
				.addClass('animated bounceInLeft'); //animazione
			//solo ora che ho renderizzato tutti gli elementi applicao il layout
			applyLayout();

		}
		/*
		 * Funcione che applica il layout egli elementi del wall
		 */
		function applyLayout(){
			var layout = new Isotope(wallId ,{
      			layoutMode: 'fitRows',
      			itemSelector: '.product-post',
     		  	resizesContainer : false
    		});
		}
		/*
		 * Funzione che riempe il tag html con i template dei prodotti complilati
		 */
		function getSearchText() {
			return $(searchBox).val();
		}
		/*
		 * Setto il cuore rosso
		 */
		function setRedHeart(el){
				$(el).children("#like").addClass("red-heart");
		}
		/*
		 * Funzione che applica il template ripetutamente ai dati di contesto
		 */
		function applyProductContext(context, myTemplate) {
			var result = "";
			context.map(function(el) {
				utils.addPicture(el);
				result += myTemplate(el);
			});

			return result;
		}
		//ricerca di un prodotto specifico
		function searchProducts(){
			var textSearch = getSearchText();
			API.getProducts(userId, textSearch).then(function(data){
				//parso il JSON dei dati ricevuti
				var rawProducts = $.parseJSON(data);
              	//prendo l'id dell'utente (se loggato) e vedo che template usare
				var useTemplate = null;
				if(userId !== null){
					useTemplate = templates.productLogged;
				}else{
					useTemplate = templates.productNoLogged;
				}
				//utilizo il template sui dati che ho ottenuto
				var parsedProducts = applyProductContext(rawProducts.body, useTemplate);
				//riempio il wall con i prodotti 
				fillWall(parsedProducts);
			},function(error){
				console.log(error);
			});
		}

		//gestore dell'evento dell'avvenuta premuta del pulsante invio sulla casella di testo
		$(searchBox).keypress(function (e) {
			if (e.keyCode == 13){
				searchProducts();
				return false;
			}

		});

		return{
			//funzione che riempie il wall con i prododtti del database
			fillWallWithProducts : function(){
				API.getProducts(userId).then(function(data){
					//parso il JSON dei dati ricevuti
					var rawProducts = $.parseJSON(data);
                  	//prendo l'id dell'utente (se loggato) e vedo che template usare
  					var useTemplate = null;
  					if(utils.isValid(userId)){
  						useTemplate = templates.productLogged;
  					}else{
  						useTemplate = templates.productNoLogged;
  					}
  					//utilizo il template sui dati che ho ottenuto
   					var parsedProducts = applyProductContext(rawProducts.body, useTemplate);
   					//riempio il wall con i prodotti 
   					fillWall(parsedProducts);
				},function(error){
					console.log(error);
				});
			},
            addPreference : function(offerId, qt, el) {
            	//setto i parametri della mia preferenza
   				preference.OfferId = offerId;
   				preference.ExternalId = userId;
   				preference.Qt = qt;
   				//richiamo l'API per settare la preferenza
   				API.addPreference(preference).then(function(data){
   					//nella callback setto il cuore rosso della preferenza
   					setRedHeart(el);
   				}, function(error){
   					console.log(error);
   				});

   			},
		};

	})();

	return WallController;

});