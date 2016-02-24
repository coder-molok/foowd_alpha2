define(function(require){

      var $ = require('jquery');
      var utils = require('Utils');
      var settings = require('utility-settings');
      var _page = require('page');                // modulo contenente elenco delle pagine piu importanti

      // impostazione globale per compatibilita' con IE e dispositivi con pesante impiego della cache:
      $.ajaxSetup({ cache: false }); // imposta un parametro '_=numeroRandom' per forzare il riutilizzo della cache;

      //modulo per la chiamata delle API  foowd
      var foowdAPI = (function(){
          //url di base delle API
          var baseUrl = settings.api;
          var siteUrl = elgg.get_site_url();

          //struttura della chiamata alle offerete 
          var offers = {
          		search : "offer?type=search",
          		prefer : "prefer", 
          		getPreferences : "prefer?type=searchTmp",
          		filterby : {
          			views : "",
          			price : "&order=Price,asc",
          			date  : "&order=Created,asc"
          		}
          	};
          var userActions = {
              search : "user?type=search",
          };
       		//imposto l'url di base delle mie chiamate
       		// function setUrl(url){
       			// baseUrl = url;
       		// }      
       		//ritorno il modulo
        	return{
        		// setBaseUrl : setUrl,
        		/*
        		 * Funzione che ritorna tutti i dati relativi ad un singolo prodotto.
             *
             * SS: se la chiamata riguarda il singolo prodotto, allora basta conoscere solo il suo id: del suo publisher non ci importa.
             * 
        		 */
        	 getProduct : function(productId ,userId ){
                 var requestURL = baseUrl + offers.search + '&Id='+productId;
                  requestURL = utils.isValid(userId)    ? requestURL + "&ExternalId=" + userId:requestURL;

                 /*SS: requestURL = utils.isValid(publisher) ? requestURL + "&Publisher=" + publisher : requestURL;*/
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
        		getProducts : function(userId, urlString, match, tags, publisher, min, max, order){
                 var requestURL = baseUrl + offers.search;
                 var deferred = $.Deferred();
                 
                 requestURL = utils.isValid(userId)    ? requestURL + "&ExternalId=" + userId           :requestURL;
                 requestURL = utils.isValid(urlString) ? requestURL + urlString                         :requestURL;
                 requestURL = utils.isValid(publisher) ? requestURL + "&Publisher=" + publisher         :requestURL;
                 requestURL = utils.isValid(tags)      ? requestURL + "&Tag=" + tags                    :requestURL;
                 requestURL = utils.isValid(order)     ? requestURL + "&order=" + order                 :requestURL;
                 requestURL = utils.isValid(match)     ? requestURL + '&match={"name":"' + match + '"}' :requestURL;

                 if(utils.isValid(min)){
                 		baseUrl += '&Id={"min":' + min;
                 		if(utils.isValid(max)){
                 			baseUrl += ',"max":' + max + '}';
                 		}
                 }else{
                 		if(utils.isValid(max)){
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

        	  },
        	  
        	  /*
               * Funzione che imposta una preferenza su un prodotto.
               */
        	  getFriend : function(userId) {
                 var deferred = $.Deferred();
                 $.ajax({
                    type : "GET",
                    url : siteUrl + "/services/api/rest/json/method?method=foowd.user.friendsOf&guid="+userId,
                    contentType : "application/json; charset=utf-8",
                    dataType : "json",
                    success : function(data, status, jqXHR) {
                       deferred.resolve(data);
                    },
                    error : function(jqXHR, status) {
                       console.log("error: "+status);
                    }
                 });

                 return deferred.promise();

        	  },
            /*
             * Funzione che ritorna le preferenze di un utente
             */
             getUserPreferences : function(userId){
                var deferred = $.Deferred();
                var requestURL = baseUrl + offers.getPreferences;
                requestURL = utils.isValid(userId) ? requestURL + "&ExternalId=" + userId : requestURL;
                $.get(requestURL, function(data){ deferred.resolve(data); });
                return deferred.promise();
             },
             getUserDetails : function(userId){
                var deferred = $.Deferred();
                var requestURL = baseUrl + userActions.search;
                var requestData = {};
                requestData.type = "search";
                requestData.ExternalId = userId;

                $.ajax({
                    type : "POST",
                    url : requestURL,
                    contentType : "application/json; charset=utf-8",
                    data : JSON.stringify(requestData),
                    dataType : "json",
                    success : function(data, status, jqXHR) {
                       deferred.resolve(data);
                    },
                    error : function(jqXHR, status) {
                       console.log("error: "+status);
                    }
                 });
                return deferred.promise();
             },
             //Serve per quando apro la pagina produttore, evita problemi con il popup
             getUserDetailsSync : function(userId){
                var deferred = $.Deferred();
                var requestURL = baseUrl + userActions.search;
                var requestData = {};
                requestData.type = "search";
                requestData.ExternalId = userId;

                $.ajax({
                	 async: false,
                    type : "POST",
                    url : requestURL,
                    contentType : "application/json; charset=utf-8",
                    data : JSON.stringify(requestData),
                    dataType : "json",
                    success : function(data, status, jqXHR) {
                       deferred.resolve(data);
                    },
                    error : function(jqXHR, status) {
                       console.log("error: "+status);
                    }
                 });
                return deferred.promise();
             },
             
             getUserPics : function(userId){
                var deferred = $.Deferred();
                var requestURL = siteUrl + "foowd_utility/image-path/";
                var requestData = {};
                requestData.ExternalId = userId;

                $.ajax({
                    type : "POST",
                    url : requestURL,
                    contentType : "application/json; charset=utf-8",
                    data : JSON.stringify(requestData),
                    dataType : "json",
                    success : function(data, status, jqXHR) {
                       deferred.resolve(data);
                    },
                    error : function(jqXHR, status) {
                       console.log("error: "+status);
                    }
                 });
                return deferred.promise();

             },
             
             purchase: function(offerId,userId,prefersList){
             	  var deferred = $.Deferred();
                var requestURL = _page.action.initPurchase;
                var requestData = {};
                requestData.OfferId = offerId;
                requestData.LeaderId = userId;
                requestData.prefersList = prefersList;

                elgg.action( requestURL, {
                    data : requestData,
                    success : function(data, status, jqXHR) {
                      console.log('data ret')
                      console.log(data)
                       deferred.resolve(data);
                    },
                    error : function(jqXHR, status) {
                      console.log(jqXHR)
                       console.log("error: "+status);
                    }

                });
                return deferred.promise();
                
             }
         };
      })();

      return foowdAPI;
});