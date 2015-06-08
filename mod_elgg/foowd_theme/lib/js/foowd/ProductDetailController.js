define(function(require){

	//carico le librerie che mi servono per il controller
	var API = require('foowdAPI');
	//var Handlebars = require('handlebars');
	var templates = require('templates');
	//var elgg = require('elgg');
	//var page = require('page'); 
	var $ = require('jquery');

	//creo il controller della pagina dettaglio
	var ProductDetailController = (function(){
		return{
			getDetailsOf : function(DOMelement){
				//prendo l'id del prodotto dall'url
				var queryUrl = elgg.parse_url(window.location.href).query;
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
				if(!queryObject.productId){
				  //reindirizzo alla pagina del wall
				  elgg.forward("/");
				}else{
					//template del prodotto singolo
					var productTemplate = templates.product;
					//richiamo la API per i dettagli del prodotto
					API.getProduct(queryObject.productId).then(function(data){
						//parso in JSON il risultato
						var rawProduct = $.parseJSON(data);
						//applico il template ai dati ricevuti
						var parsedProducts = productTemplate(rawProduct);
						//lo metto nell'elemento HTML che passato alla funzione
						$(DOMelement)
			  	    		.append(parsedProducts)
							.addClass('animated bounceInLeft'); //animazione
					}, function(error){
						//gestico l'errore
						console.log(error);
					});
				}
			}
		}
	})();

	return ProductDetailController;
});