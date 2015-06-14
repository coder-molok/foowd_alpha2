define(function(require){

      var $ = require('jquery');
      var Utils = require('Utils');
      var settings = require('utility-settings');

      //modulo per la chiamata delle API  foowd
      var foowdAPI = (function(){

      //url di base delle API
      var baseUrl = settings.api;

      //struttura della chiamata alle offerete 
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
   		//imposto l'url di base delle mie chiamate
   		function setUrl(url){
   			baseUrl = url;
   		}      
   		//ritorno il modulo
    	return{
    		setBaseUrl : setUrl,
    		/*
    		 * Funzione che ritorna tutti i dati relativi ad un singolo prodotto.
    		 */
    		getProduct : function(productId){
             var requestURL = baseUrl + offers.search + '&Id={"min":' + productId + ', "max":' + productId + '}';
             var deferred = $.Deferred();
             $.get(requestURL, function(data){ deferred.resolve(data); });
             return deferred.promise();
          },
          /*
    		 * Funzione che ritorna tutti i dati relativi ad un singolo prodotto.
           * @param userId     : id dell'utente
           * @param match      : stringa di confronto per il nome dell'offerta
           * @param tags       : tag dell'offerta
           * @param publisher  : chi l'ha pubblicata
           * @param min        : id minimo offerta
           * @param max        : id massimo offerta
           * @param prder      : ordine di arrivo dei dati
    		 */
    		getProducts : function(userId, match, tags, publisher, min, max, order){
             var requestURL = baseUrl + offers.search;
             var deferred = $.Deferred();
             
             requestURL = Utils.isValid(userId)    ? requestURL + "&ExternalId=" + userId           :requestURL;
             requestURL = Utils.isValid(publisher) ? requestURL + "&Publisher=" + publisher         :requestURL;
             requestURL = Utils.isValid(tags)      ? requestURL + "&Tag=" + tags                    :requestURL;
             requestURL = Utils.isValid(order)     ? requestURL + "&order=" + order                 :requestURL;
             requestURL = Utils.isValid(match)     ? requestURL + '&match={"name":"' + match + '"}' :requestURL;

             if(Utils.isValid(min)){
             		baseUrl += '&Id={"min":' + min;
             		if(Utils.isValid(max)){
             			baseUrl += ',"max":' + max + '}';
             		}
             }else{
             		if(Utils.isValid(max)){
             			baseUrl += '&Id={"max":' + max +'}';
             		}
             }

             $.get(requestURL, function(data){ deferred.resolve(data); });
             return deferred.promise();
          },
          /*
           * Funzione che imposta una preferenza su un prodotto.
           */
          addPreference : function(preference) {
             var deferred = $.Deferred();
             $.ajax({
                type : "POST",
                url : baseUrl + offers.prefer,
                contentType : "application/json; charset=utf-8",
                data : JSON.stringify(preference),
                dataType : "json",
                success : function(data, status, jqXHR) {
                   deferred.resolve(data);
                },
                error : function(jqXHR, status) {
                   console.log("error: "+status);
                }
             });

             return deferred.promise();

    	  }
     };
  })();

      return foowdAPI;
});