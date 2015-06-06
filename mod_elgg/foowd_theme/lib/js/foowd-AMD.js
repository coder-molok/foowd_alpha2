   define(function(require) {

   	/**
   	 * Created by predo1 on 22/04/15.
   	 */
   	
   	var Handlebars = require('handlebars');
      var templates = require('templates');
      var elgg = require('elgg');
      var page = require('page'); 
      
      // esempio di utilizzo plugin Page
      // alert(page.all);

      var foowd = (function() {

   	    /*
   	  	 *  Questo è un modulo che contiene tutte le funzionalità del client foowd
   		 */

   		// inizializzo l'url a cui verranno fiatte le chiamate
   		var baseUrl = "";
   		//ordinamento dei risultati delle offerte
   		var filterPreference = "";
   		//input di ricerca delle offerte
   		var searchPreference = "";
   		//user id per controllare se l'utente e loggato oppure no
   		var userId = 0;
   		
   		/*
   		 * L'oggeto offers contiene gli URL a cui fare le chiamate alle API.
   		 * E' organizzato in modo tale da essere di facile lettura e comprensione, e facilmente espandibile.
   		 */
   		var offers = {
   			search : "offer?type=search",
   			prefer : "prefer", 
   			getPreferences : "prefer?type=search",
   			filterby : {
   				views : "",
   				price : "&order=Price,asc",
   				date  : "&order=Created,asc"
   			}
   		};

   		/*
   		 * Definisco i parametri di default per l'inserimento di una preferenza
   		 */
   		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "0",
   		};
   		
   		/*
   		 * Ci sono due versioni del template:
   		 *  - una per gli utenti loggati con tutte le funzioni per aggiungere i prodotti alle preferenze
   		 *  - una senza funzionalità per gli utenti visitatori
   		 */

   		// var productLoggedTemplate = Handlebars.templates.productLogged;
   		// var productNoLoggedTemplate = Handlebars.templates.productNoLogged;
   		var productLoggedTemplate = templates.productLogged;
         var productNoLoggedTemplate = templates.productNoLogged;


   		/*
   		* Ho registrato un helper handlebars, per modificare la classe del cuore sulla preferenza
   		* in base ai dati che arrivano decido se applicare la classe oppure no
   		*/
   		
   		Handlebars.registerHelper('prefer', function(object) {
   	 		var result = "";
   	 		if(object.data.root.prefer != null){
   	 			result = "red-heart";
   	 		}
   			return new Handlebars.SafeString(result);
   		});



   		//tag html dove andiamo a mettere il template compilato
   		var wallId = ".wall";

   		//search box id
   		var searchBox = "#searchText";

   		/*
   		 * Funzione che riempe il tag html con i template dei prodotti complilati
   		 */
   		function fillWall(content) {
   			$(wallId)
   				//.html(content) //contenuto 
               .append(content)
   				.addClass('animated bounceInLeft'); //animazione
   		}
         /*
          * Funzione che aggiunge a ciascuna offerta il membro picture, utilizzato nel template
          */
         
         function addPicture(content) {
            var offers = content.body;
            for( var i in offers){
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
   		 * Funzione che colora di rosso il cuore se ho espresso la preferenza
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

   		return {

   			setBaseUrl : function(newUrl){
   				baseUrl = newUrl;
   			},
   			setUserId : function(newId){
   				userId = newId;
   			},
   			/*
   			 * Funzione che prende i dati da remoto e li trasforma nei prodotti del wall
   			 */
   			getProducts : function() {
   				// uso lo user id per capire se un utente è loggato o meno
   				// in base a quello scelgo il template da utilizzare e parametrizzo la chiamata

   				var useTemplate;
   				var urlParams;

   				if(userId != 0){
   					useTemplate = productLoggedTemplate;
   					urlParams = "&ExternalId=" + userId;
   				}else{
   					useTemplate = productNoLoggedTemplate;
   					urlParams = "";
   				}

   				urlParams += searchPreference + filterPreference;

   				$.get(baseUrl + offers.search + urlParams, function(data) {
   					var rawProducts = $.parseJSON(data);
                  addPicture(rawProducts);
   					var parsedProducts = applyProductContext(rawProducts.body, useTemplate);
   					fillWall(parsedProducts);
   				});

   			},

   			/*
   			 * Funzione che aggiunge una preferenza dell'utente
   			 */

   			addPreference : function(offerId, qt, el) {
   				preference.OfferId = offerId;
   				preference.ExternalId = userId;
   				preference.Qt = qt;
   				
   				jQuery.ajax({
   					type : "POST",
   					url : baseUrl + offers.prefer,
   					contentType : "application/json; charset=utf-8",
   					data : JSON.stringify(preference),
   					dataType : "json",
   					success : function(data, status, jqXHR) {
   						//TODO tenere in considerazione errori
   						setRedHeart(el);
   					},

   					error : function(jqXHR, status) {
   						console.log("error: "+status);
   					}
   				});


   			},
   			filterBy : function(filterParam){
   				filterPreference = offers.filterby[filterParam];
   				foowd.getProducts();
   			},
   			searchOffers : function(){
   				var search = $(searchBox).val();
   				if(search != ""){
   					searchPreference = "&Tag=" + search;
   					foowd.getProducts();
   				}
   			}
   		};

   	})(); 


   	// ritorno il modulo per require
   	return foowd;

}); // chiusura define