/*
 * Alcune funzioni di utility che possono essere utili nel progetto
 */

define(function(require){

	var elgg = require('elgg');

	var Utils = (function(){
		//controlla che una data variabile sia valida
		function isValid(el){
			return el !== undefined && el !== null
		}
		//re-indirizza alla pagina di dettaglio del modulo
		function goProductDetail(productId){
               elgg.forward("/detail?productId=" + productId);
        }
        //re-indirizza sul profilo dell'utente
        function goToUserProfile(){
        	if(elgg.is_logged_in()){
            	elgg.forward("/profile/" + elgg.get_logged_in_user_entity().username);
            }else{
            	elgg.forward("/login");
            }
        }
        //funzione che re indirizza su una pagina generica
		function goTo(page){
			page = isValid(page) ? page : "";
			elgg.forward("/" + page);
		}

        return{
        	isValid: isValid,
        	goProductDetail: goProductDetail,
        	goToUserProfile: goToUserProfile,
        	goTo: goTo,
        };
	})();;

	return Utils;
});