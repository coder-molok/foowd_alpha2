/**
 * Created by predo1 on 22/04/15.
 */

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
	var offers;
	offers = {
		search :"type=search",
		filterby : {
			views : "",
			price : "&order=Price,asc",
			date  : "&order=Created,asc"
		}
	};

	/*
	 * Definisco i parametri standard per l'inserimento delle offerte
	 */
	var preference;
	preference = {
		url : "prefer",
		data : {
			type : "create",
			Qt : "0",
			UserId : "",
			OfferId : ""
		}
	};
	
	/*
	 * Ci sono due versioni del template:
	 *  - una per gli utenti loggati con tutte le funzioni per aggiungere i prodotti alle preferenze
	 *  - una senza funzionalità per gli utenti visitatori
	 */

	var productLoggedTemplate = Handlebars.templates.productLogged;
	var productNoLoggedTemplate = Handlebars.templates.productNoLogged;
	
	//tag html dove andiamo a mettere il template compilato
	var wallId = ".wall";

	//search box id
	var searchBox = "#searchText";

	/*
	 * Funzione che riempe il tag html con i template dei prodotti complilati
	 */
	function fillWall(content) {
		$(wallId).html(content);
	}
	/*
	 * Funzione che riempe il tag html con i template dei prodotti complilati
	 */
	function getSearchText() {
		return $(searchBox).val();
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
			baseUrl = newUrl + "offer?";
		},
		setUserId : function(newId){
			userId = newId;
		},
		/*
		 * Funzione che prende i dati da remoto e li trasforma nei prodotti del wall
		 */
		getProducts : function() {
			// uso lo user id per capire se un utente è loggato o meno
			// in base a quello scelgo il template da utilizzare
			var useTemplate = (userId == 0) ? productNoLoggedTemplate : productLoggedTemplate;
			
			var urlParams ="";

			$.get( baseUrl + offers.search + searchPreference + filterPreference, function(data) {
				var rawProducts = $.parseJSON(data);
				var parsedProducts = applyProductContext(rawProducts.body, useTemplate);
				fillWall(parsedProducts);
			});

		},

		/*
		 * Funzione che aggiunge una preferenza dell'utente
		 */

		addPreference : function(offerId,qt) {
			
			preference.data.OfferId=offerId;
			preference.data.UserId=userId;
			preference.data.Qt=qt;
			
			jQuery.ajax({
				type : "POST",
				url : baseUrl+preference.url,
				contentType : "application/json; charset=utf-8",
				data :JSON.stringify(preference.data),
				dataType : "json",
				success : function(data, status, jqXHR) {
					//TODO tenere in considerazione errori
					console.log("success: "+data);
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
			//TODO: serch by text in some way
			searchPreference = "&Tag=" + $(searchBox).val();
			foowd.getProducts();
		}
	};

})(); 