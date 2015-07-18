/*
 * Alcune funzioni di utility che possono essere utili nel progetto
 */

define(function(require){
    //modulo di elgg
	var elgg = require('elgg');
    //modulo page
    var page = require('page');
    //templates
    var templates = require('templates');

	var Utils = (function(){
		//controlla che una data variabile sia valida
		function isValid(el){
			return el !== undefined && el !== null
		}
        //converte un array di un singolo elemento in un oggetto con gli attributi delll'elemento
        function singleElToObj(array){
            if(array.length == 1){
                return array[0];
            }
            return array
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
        /*
         * Funzione che aggiunge ad una offerta il membro picture, utilizzato nel template
         */
        function addPicture(offer) {
            var newObj = offer;
            if(isValid(newObj)){
                newObj.picture = page.offerFolder + '/User-' + newObj.Publisher + '/' + newObj.Id + '/medium/' + newObj.Id + '.jpg';
            }
            return newObj;
        }
        /*
         * Funzione che mi setta il campo 'logged su di un oggetto se l'utente è loggato
         */
        function setLoggedFlag(object, userId){
            var newObj = object;
            
            if(isValid(object)){
                if(isValid(userId) && userId != 0){
                    newObj.logged = true;
                }
            }

            return newObj;
        } 
        /*
         * Funzione che mi carica l'header
         */
        function loadNavbar(search){
            search = isValid(search) ? search : false;
            var context = {
                "search" : search
            };
            $('.foowd-navbar').html(templates.navbar(context));
        }


        return{
        	isValid: isValid,
            singleElToObj, singleElToObj,
        	goProductDetail: goProductDetail,
        	goToUserProfile: goToUserProfile,
        	goTo: goTo,
            addPicture : addPicture,
            setLoggedFlag : setLoggedFlag,
            loadNavbar : loadNavbar,
        };
	})();;

	return Utils;
});