/*
 * Utility function per lo sviluppo della pate frontend
 * Author : Marco Predari
 *
 * L'idea è quella di wrappare le funzionalità fornite da elgg
 * in modo da non utilizzarle direttamente nei controller
 * (aumentare il disaccopiamento)
 *
 *
 */

define(function(require){
    
    //modulo di elgg
	var elgg = require('elgg');
    //modulo page
    var page = require('page');
    //templates
    var templates = require('templates');

	var Utils = (function(){
       /*
        * Seriusly? I have to comment this?
        */
		function isValid(el){
			return el !== undefined && el !== null
		}

       /*
        *  Converte un array di un singolo elemento in un oggetto con 
        *  gli attributi delll'elemento
        */
        function singleElToObj(array){
            if(array.length == 1){
                return array[0];
            }
            return array;
        }

	   /*
        * Re-indirizza verso una pagina specificando una parametro
        * 
        */
		function go2(page, parameter, parameterValue,event){
			if(event.defaultPrevented){

				return;
			}
            if(isValid(page) && isValid(parameter) && isValid(parameterValue)){
                elgg.forward("/" + page + "?" + parameter + "=" + parameterValue);  
            }
        }

       /*
        * Re-indirizza verso una pagina generica
        */
		function goTo(page){
			if(isValid(page)){
                elgg.forward("/" + page);
            }
		}

        /*
         * Ritorno l'url
         */
        function uriTo(page){
            if(isValid(page)){
                return elgg.get_site_url() + page;
            }
        }

        /*
         * ritorno l'url del dettaglio prodotto
         */
        function uriProductDetail(parameterValue){
            if(isValid(parameterValue)){
                return elgg.get_site_url() + "detail?productId=" + parameterValue;  
            }
        }

       /*
        * Generatore casuale delle dimensioni delle immagini del wall
        */
        function randomPictureSize(offerId){
            offerId = isValid(offerId) ? offerId : 0;
            var monthDay = new Date().getDate();
            var rand = (offerId + monthDay) % 3;
            if(rand === 0){
                return 'medium';
            }else{
                return 'big';
            }
        }

       /*
        * Funzione che aggiunge ad una offerta il membro picture, utilizzato nel template
        */
        function addPicture(offer, pictureSize){
            var newObj = offer;
            pictureSize = isValid(pictureSize) ? pictureSize : 'big';
            if(isValid(newObj)){
                newObj.picture = page.foowdStorage + '/User-' + newObj.Publisher + '/offers/' + newObj.Id + '/' + pictureSize + '/' + newObj.Id + '.jpg';
            }
            return newObj;
        }

        function addProfilePicture(obj, pic){
            var newObj = obj;
            if (isValid(newObj)){
                newObj.avatar = (pic.avatar !== null) ? page.foowdStorage + 'User-' + newObj.Publisher + '/' + pic.avatar : elgg.get_site_url() + '_graphics/icons/user/defaultmedium.gif';
            }
            return newObj;
        }

       /*
        * Funzione che mi setta il campo 'logged su di un oggetto se l'utente è loggato
        */
        function setLoggedFlag(object, userId){
            var newObj = object;
            
            if(isValid(object)){
                if(isValid(userId) && userId !== 0){
                    newObj.logged = true;
                }
            }

            return newObj;
        } 
        
        /*
        * Funzione che  setta il campo group, se e' attiva o meno la funzionalita' group
        */
        function setLoggedGroup(object, group){
            var newObj = object;
            
            if(isValid(object)){
                newObj.group = group;
            }

            return newObj;
        } 


       /*
        * Ritorna lo user id della sessione corrente
        */
        function getUserId () {
            return elgg.get_logged_in_user_guid() === 0 ? null : elgg.get_logged_in_user_guid();
        }
        
        
       /*
        * Vede se un utente è loggato
        */
        function isUserLogged(){
            var userId = getUserId()
            return isValid(userId);
        }
       
       /*
        * Ritorna un oggetto con i parametri dell'url
        */
        function getUrlArgs(){
            var queryUrl = elgg.parse_url(window.location.href).query;
            var queryObject = {};
            if(isValid(queryUrl)){
                //splitto i vari parametri dell'url
                var sURLVariables = queryUrl.split('&');
                //creo l'oggeto finale
                var queryObject = {};
                //aggiungo i parametri all'oggetto
                for(var i = 0; i < sURLVariables.length ; i++){
                    var args = sURLVariables[i].split('=');
                    queryObject[args[0]] = args[1];
                }
            }
            return queryObject;
        }

        /**
         * preparo un'offerta: svolgo dei conti relativi al totale delle preferenze nello switch utente/gruppo
         */
        function offerPrepare(el, group){
            // calcolo i totali per utente e per gruppo
            el.offer.totalQtUser = 0;
            el.offer.totalQtGroup = 0;
            el.offer.prefers = [];
            for(var i in el.prefers){
                // sono interessato a conteggiare solo quelle in stato newest
                if(el.prefers[i].State != "newest") continue;
                el.offer.prefers.push(el.prefers[i].Id);
                if(getUserId() == el.prefers[i].UserId) el.offer.totalQtUser += el.prefers[i].Qt;
                el.offer.totalQtGroup += el.prefers[i].Qt;
            }
            el.offer.prefers = el.offer.prefers.join(',');
            el.offer.productDetailUri = elgg.get_site_url() + 'detail?productId=' + el.offer.Id;
            //aggiungo l'immmagine
            el.offer = addPicture(el.offer, utils.randomPictureSize(el.offer.Id));
            //se l'utente è loggato aggiungo un dato al contesto
            el.offer = setLoggedFlag(el.offer, getUserId());

            // di default aggiungo anche il gruppo
            el.offer = setLoggedGroup(el.offer, group);
            el.offer.totalQt = (group) ? el.offer.totalQtGroup : el.offer.totalQtUser ;

            return el;
        }

        return{
        	isValid             : isValid,
            singleElToObj       : singleElToObj,
            go2                 : go2,
        	goTo                : goTo,
            randomPictureSize   : randomPictureSize,
            addPicture          : addPicture,
            addProfilePicture   : addProfilePicture,
            setLoggedFlag       : setLoggedFlag,
            setLoggedGroup      : setLoggedGroup,
            getUserId           : getUserId,
            isUserLogged        : isUserLogged,
            getUrlArgs          : getUrlArgs,
            offerPrepare        : offerPrepare ,
            uriTo               : uriTo,
            uriProductDetail    : uriProductDetail
        };

	})();
    window.utils = Utils;
	return Utils;
});