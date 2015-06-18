define(function(require){

	/*
	 * DIPENDEZE MODULO ------------------------------------------------------------------------
     */

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


	var WallController = (function(){

		/*
		 * IMPOSTAZIONI MODULO ------------------------------------------------------------------------
		 */


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
		 * FUNZIONI PRIVATE DEL MODULO -----------------------------------------------------------
		 */

		/*
		 * Funzione che riempe il tag html con i template dei prodotti complilati
		 */
		function fillWall(content) {
			$(wallId)
		  	    .html(content)
				.addClass('animated bounceInLeft'); //animazione
			//solo ora che ho renderizzato tutti gli elementi applicao il layout

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
		/*
		 * Ricerca dei prodotti in base alla chiave testuale
		 */
		function searchProducts(){
			var textSearch = getSearchText();
			API.getProducts(userId, textSearch).then(function(data){
				//parso il JSON dei dati ricevuti
				var rawProducts = $.parseJSON(data);
              	//prendo l'id dell'utente (se loggato) e vedo che template usare
				if(rawProducts.body.length > 0){
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
				}else{
					$(searchBox).trigger('failedSearch');
				}

			},function(error){
				console.log(error);
			});
		}

		/*
		 * Funzione che riempie il wall con i prododtti del database
		 */
		function fillWallWithProducts(){
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
		}

		/*
		 * Funzione che aggiunge una preferenza
		 */
		function addPreference(offerId, qt, el) {
    		//setto i parametri della mia preferenza
			preference.OfferId = offerId;
			preference.ExternalId = userId;
			preference.Qt = qt;
			//richiamo l'API per settare la preferenza
			API.addPreference(preference).then(function(data){
				//nella callback setto il cuore rosso della preferenza
				setRedHeart(el);

				fillWallWithProducts();
				$(searchBox).trigger('preferenceAdded');
			}, function(error){
				$(searchBox).trigger('preferenceError');
				console.log(error);
			});

		}

		/*
		 * GESTIONE EVENTI ------------------------------------------------------------------------
		 */

		//gestore dell'evento dell'avvenuta premuta del pulsante invio sulla casella di testo
		$(searchBox).keypress(function (e) {
			if (e.keyCode == 13){
				searchProducts();
				return false;
			}

		});
		//notifica errore nel caso la ricerca testuale non ha prodotto risultati
		$(searchBox).on('failedSearch', function(e){
			$('#foowd-error').text('La tua ricerca non ha prodotto risultati');
			$('#foowd-error').fadeIn(500).delay(3000).fadeOut(500);
		});
		//notifica positiva nel caso la preferenza è stata aggiunta correttamente
		$(searchBox).on('preferenceAdded', function(e){
			$('#foowd-success').text('La tua preferenza è stata aggiunta');
			$('#foowd-success').fadeIn(500).delay(3000).fadeOut(500);
		});
		//notifica di errore nel caso la preferenza non fosse stata aggiunta
		$(searchBox).on('preferenceError', function(e){
			$('#foowd-error').text("C'è stato un errore durante l'aggiuta della tua preferenza");
			$('#foowd-error').fadeIn(500).delay(3000).fadeOut(500);
		});

		/*
		 * METODI PUBBLICI ------------------------------------------------------------------------
		 */

		return{
			fillWallWithProducts : fillWallWithProducts,
			addPreference : addPreference
		};

	})();

	return WallController;

});