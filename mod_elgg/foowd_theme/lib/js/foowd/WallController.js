define(function(require){

	var API = require('foowdAPI');
	var Handlebars = require('handlebars');
	var templates = require('templates');
	var elgg = require('elgg');
	var page = require('page'); 
	var $ = require('jquery');

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

   		/*
		 * Ho registrato un helper handlebars, per modificare la classe del cuore sulla preferenza
	     * in base ai dati che arrivano decido se applicare la classe oppure no
	     */
	
		Handlebars.registerHelper('prefer', function(object) {
			var result = "";
			if(object.data.root.prefer !== null){
				result = "red-heart";
			}
			return new Handlebars.SafeString(result);
		});

   		//userId reference
   		var userId = null;
		/*
		 * Funzione che riempe il tag html con i template dei prodotti complilati
		 */
		function fillWall(content) {
			$(wallId)
		  	    .html(content)
				.addClass('animated bounceInLeft'); //animazione
		}
		/*
		 * Funzione che aggiunge a ciascuna offerta il membro picture, utilizzato nel template
		 */
		function addPicture(content) {
			var offers = content.body;
			for(var i in offers){
			   var of = offers[i];
			   of.picture = page.offerFolder + '/User-' + of.Publisher + '/' + of.Id + '/medium/' + of.Id + '.jpg';
			}
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
				result += myTemplate(el);
			});
			return result;
		}
		//ricerca di un prodotto specifico
		function searchProducts(){
			var textSearch = getSearchText();
			//TODO : capire in quale parametro va il testo della ricerca
			//		 nel caso vedere anche il file delle API
   				API.getProducts(userId).then(function(data){
					//parso il JSON dei dati ricevuti
					var rawProducts = $.parseJSON(data);
					//(? Simo) creo aggiunga l'immagine ai dati
                  	addPicture(rawProducts);
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
			//funzione che setta lo user id all'interno del modulo
			setLocalUserId : function(id){
				userId = id;
			},
			//re-indirizza alla pagina di dettaglio del modulo
			goProductDetail : function(productId){
               elgg.forward("/detail?productId=" + productId);
            },
            //funzione che re indirizza su una pagina generica
            goToUserProfile : function(){
            	elgg.forward("/profile/" + elgg.get_logged_in_user_entity().username);
            	//elgg.forward("/profile" + elgg);
            },
			//funzione che riempie il wall con i prododtti del database
			fillWallWithProducts : function(){
				API.getProducts(userId).then(function(data){
					//parso il JSON dei dati ricevuti
					var rawProducts = $.parseJSON(data);
					//(? Simo) creo aggiunga l'immagine ai dati
                  	addPicture(rawProducts);
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
   			searchProducts : searchProducts
		};

	})();

	return WallController;

});