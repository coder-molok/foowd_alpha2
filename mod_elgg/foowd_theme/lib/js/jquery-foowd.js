/**
 * Modulo jquery implementato per riutilizzare alcune funzioni che spesso utilizzo.
 * Per funzionalita' utilizzo un pattern amd-compatibile che carica il plugin come modulo se richiesto tramire requirejs,
 * o in caso contrario lo esporta sulla variabile globale jquery.
 *
 * schema iniziale da: https://github.com/umdjs/umd/blob/master/templates/jqueryPlugin.js
 */


 ;(function (factory) {
     if (typeof define === 'function' && define.amd) {
         // AMD. Register as an anonymous module.
         define(['jquery'], factory);
     } else if (typeof module === 'object' && module.exports) {
         // Node/CommonJS
         module.exports = function( root, jQuery ) {
             if ( jQuery === undefined ) {
                 // require('jQuery') returns a factory that requires window to
                 // build a jQuery instance, we normalize how we use modules
                 // that require this pattern but the window provided is a noop
                 // if it's defined (how jquery works)
                 if ( typeof window !== 'undefined' ) {
                     jQuery = require('jquery');
                 }
                 else {
                     jQuery = require('jquery')(root);
                 }
             }
             factory(jQuery);
             return jQuery;
         };
     } else {
         // Browser globals
         factory(jQuery);
     }
 }(function ($) {

 	/**
 	 * action e' il nome del metodo da invocare. Viene utilizzato per evitare il rischio di sovrascrivere altri metodi jquery. In questo modo l'unica sovrascrittura avviene per foowd
 	 */
 	
 	// se dichiaro una variabile in questa zona, la variabile diventa comune a TUTTE le chiamate di $(elemento/i).foowd(), ovvero si comporta come una static di questo modulo
 	// var ModuleGlobal = new Date().toLocaleString();
 	// console.log('ModuleGlobal: '+ ModuleGlobal);

 	// ad ogni chiamata $().foowd() questa funzione viene reinizializzata
    $.fn.foowd = function (action, options) { 

    	// Plugin defaults – added as a property on our plugin function.
    	$.fn.foowd.defaults = {
    	   "info": "Foowd jQuery Plugin. Verision 1.0.0",
    	   "callback": function(){}, // la inizializzo a vuota, cosi' in tutte le funzioni che implementano una callback non avro' bisogno di fare un check per sincerarmi che sia una funzione
    	   "args": []  // argomenti per la callback: viene utilizzata con apply
        };
     	// in questa sezione il this e' riferito all'oggetto jquery, in quanto sto usando lo scope di una sua variabile
     	
     	// We can use the extend method to merge options/settings as usual:
     	// But with the added first parameter of TRUE to signify a DEEP COPY:
     	var settings = $.extend( true, {}, $.fn.foowd.defaults, options );

     	// variabile in cui carico la funzione relativa alla specifica azione
     	var __foowdFunc;

     	/**
     	 * Riferimento: http://stackoverflow.com/questions/3877027/jquery-callback-on-image-load-even-when-the-image-is-cached
     	 *
     	 * Chiamo la funzione onload, triggerandola una volta sola, anche per le immagini gia' caricate nel dom, molto utile!
     	 */
     	if( action == 'onLoad' ){
            // dare un nome alla funzione e' utile per via dei developer tool, in quanto cosi' non figurano anonime
            __foowdFunc = function onLoad(opt){
                $(this).one('load', function(){
     				opt.callback.apply(this, opt.args);
     			}).each(function(){
     				if(this.complete) $(this).load();
     			});
     		}
     	}

     	/**
     	 * Riferimento: http://stackoverflow.com/questions/8572875/on-keypress-when-stop-after-x-seconds-call-function
     	 * Quando sull'elemento viene implementato il keypress, si occupa di:
     	 * @param opt.delay  	... aspettare un tempo delay per triggerare la callback
     	 * @param opt.event 	... l'evento da bindare
     	 * @param opt.callback 	... funzione da invocare passato il tempo delay. Non ha bisogno di controlli perche' l'ho gia' istanziata nei defaults e viene sovrascritta grazie a $.extend(), senza realmente toccare i defaults originali
     	 */
     	if( action == 'bindDelay' ){
     		// Non uso una closure perche' la funzione contiene 
     		// $('input').foowd('bindDelay'): 
     		// 	- la variabile opt verrebbe istanziata una sola volta per tutto il gruppo, in quanto ho invocato solo una volta la funzione $.fn.foowd()
     		// 	- ciascuna funzione viene caricata in __foowFunc con un proprio scope, pertanto timeouts risulta personale per ogni each.
     		// 	Se avessi la necessita' potrei, prima di __foowdFunc creare una variabile privata comune PER TUTTE LE SELEZIONI e parametrizzata per ogni istanza di .foowd (quando viene invocato questo codice si rigenera da zero)
			
			// Appunto:     		
     		// una qualsiasi variabile dichiarata in questo punto potrebbe rimanere comune a UNA SINGOLA CHIAMATA di $(elemento/i).foowd(), ovvero comune a tutti gli elementi del selettore jquery. Devo pero' svolgere delle closure...
     		// var SelectorGlobal = new Date().toLocaleString();
     		// console.log('SelectorGlobal: '+ SelectorGlobal);
     		__foowdFunc = function bindDelay(opt){
     			timer = (typeof opt.delay === "number") ? opt.delay : 300;
     			var timeouts;
     			$(this).bind(opt.event, function(event) {
     			        var that = this;
     			    	clearTimeout(timeouts);
     			     	timeouts = setTimeout(function() {
     			    	opt.callback.call(that, event);
     				}, timer);
     			});
     		}	
     	}


        /**
         * forzo l'elemeno ad essere riaggiornato, senza bisogno di reutilizzare il selettore esplicitamente
         * @param  {[type]} action [description]
         * @return {[type]}        [description]
         */
        if( action == 'refreshEl' ){
            __foowdFunc = function refreshEl(){
                return $(this.selector);
            }
        }


     	// Iterate and reformat each matched element. 
     	// Return this.each() is usefull for:
     	// 	1- automatically iterate jquery selection
     	// 	2- chain, since .each() implicitally return "this" referred to jquery objet
     	return this.each(function(){
     		// object selection context for invoked functions
     		__foowdFunc.call(this, options);
     	});


     };


 }));


