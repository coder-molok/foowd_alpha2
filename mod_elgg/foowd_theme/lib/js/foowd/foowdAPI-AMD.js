define(function(require){

	  var $ = require('jquery');
      
      //modulo per la chiamata delle API  foowd
      var foowdAPI = (function(){

      	//url di base delle API
      	var baseUrl = "";

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
      		 */
      		getProducts : function(userId, publisher, min, max, tags, order){
               var requestURL = baseUrl + offers.search;
               var deferred = $.Deferred();
               
               requestURL = userId    != undefined ? requestURL + "&ExternalId=" + userId : requestURL;
               requestURL = publisher != undefined ? requestURL + "&Publisher=" + publisher : requestURL;
               requestURL = tags      != undefined ? requestURL + "&Tag=" + tags : requestURL;
               requestURL = order     != undefined ? requestURL + "&order=" + order : requestURL;

               if(min != undefined){
               		baseUrl += '&Id={"min":' + min;
               		if(max != undefined){
               			baseUrl += ', "max":' + max + '}';
               		}
               }else{
               		if(max != undefined){
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
         }
      })();

      return foowdAPI;
});