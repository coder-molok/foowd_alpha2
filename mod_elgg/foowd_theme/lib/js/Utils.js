/*
 * Utility function per lo sviluppo della pate frontend
 * Author : Marco Predari
 *
 * L'idea è quella di wrappare le funzionalità fornite da elgg
 * in modo da non utilizzarle direttamente nei controller
 * (aumentare il disaccopiamento)
 *
 * Beta : all'interno ci sono alcune funzioni per caricare la barra di ricerca del sito
 * appena trovo una soluzione migliore dovranno essere spostate
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
		function go2(page, parameter, parameterValue){
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
                newObj.avatar = page.foowdStorage + '/User-' + newObj.Publisher + '/' + pic; 
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

        return{
        	isValid           : isValid,
            singleElToObj     : singleElToObj,
            go2               : go2,
        	goTo              : goTo,
            randomPictureSize : randomPictureSize,
            addPicture        : addPicture,
            addProfilePicture : addProfilePicture,
            setLoggedFlag     : setLoggedFlag,
            getUserId         : getUserId,
            isUserLogged      : isUserLogged,
            getUrlArgs        : getUrlArgs,
        };

	})();
    window.utils = Utils;
	return Utils;
});