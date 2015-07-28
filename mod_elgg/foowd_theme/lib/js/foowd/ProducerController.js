define(function(require){

	/*
	 * DIPENDEZE MODULO ------------------------------------------------------------------------
     */

	var $ = require('jquery');
	var API = require('FoowdAPI');
	var Navbar = require('NavbarController');
	var templates = require('templates');
	var utils = require('Utils');
	var WallController = require('WallController');

	var ProducerController = (function(){

		/*
		 * IMPOSTAZIONI MODULO ------------------------------------------------------------------------
		 */

		var producerInfoContainer = "#producer-profile";
		var producerWallContainer = "#producer-wall";
		var postProgressBarClass = ".mini-progress";
		//userId reference
   		var userId = null;		
		//prototipo di una prefereza
		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "0",
   		};
   	   /*
		* FUNZIONI PRIVATE DEL MODULO -----------------------------------------------------------
		*/

		//nel controller devo essere sicuro che il dom sia stato casricato correttamente
		function _stateCheck(){
			switch(document.readyState){
				case "loading":
					document.onreadystatechange = function (){
						_stateCheck();
					}
				break;
				case "interactive":
				case "complete": 
					_init();
				break;
			}
		}
		//inizializzazione del controller
		function _init(){
			//carico l'id utente se loggato
			userId = utils.getUserId()
			//carico l'header 
			Navbar.loadNavbar();
			//carico le informazioni dell'utente
			_getProducerInfo();
			//carico il wall con i template
			_getProducerWall();
			//carico il carosello immagini
			_initCarousel();
			$(document).trigger('producer-page-loaded');
		}

		function _getProducerWall(){
			var producer = utils.getUrlArgs();
			if(utils.isValid(producer.producerId)){
				API.getProducts(userId,null,null,producer.producerId).then(function(data){
					var rawProducts = data.body;
					var parsedProducts = _applyProducerWallContext(rawProducts);
					_fillProducerWall(parsedProducts);
					$(document).trigger('producer-wall-loaded');
				},function(error){
					console.log(error);
				});
			}
		}
		/*
		* Funzione che applica il template ripetutamente ai dati di contesto
		*/
		function _applyProducerWallContext(context) {
			var result = "";
			context.map(function(el) {
				//aggiungo l'immmagine
				el = utils.addPicture(el);
				//se l'utente è loggato aggiungo un dato al contesto
				el = utils.setLoggedFlag(el, userId);
				//l'array prefer contiene tutti gli utenti che hanno espresso la preferenza sull'offerta
				//in questo caso specificando sempre l'ExternaId nella richiesta quindi mi ritornerà 
				//sempre un solo utente. Per comodità converto l'array contenente il singolo utente
				//in un oggetto con i dati dell'utente
				if(el.logged){
					el.prefer = utils.singleElToObj(el.prefer)
				}

				result += templates.productPost(el);

			});

			return result;
		}
		/*
		 * Funzione che riempe il tag html con i template dei prodotti complilati
		 */
		function _fillProducerWall(content) {
			$(producerWallContainer)
		  	    .html(content);
				//.addClass('animated bounceInLeft'); //animazione
		}

		function _getProducerInfo(){
			var producer = utils.getUrlArgs();
			if (utils.isValid(producer.producerId)){
				API.getUserDetails(producer.producerId).then(function(data){
					var userData = data.body;
					var parsedUserData = _applyProducerProfleContext(userData);
					_fillProducerProfile(parsedUserData);
				}, function(error){	
					console.log(error);
				});
			}
		}

		function _applyProducerProfleContext(producerData){
			return templates.producerProfile(producerData);
		}

		function _fillProducerProfile(content){
			$(producerInfoContainer).html(content);
			$(document).trigger('producer-info-loaded');
		}
		/*
		 * Funzione che riempe le barre di progresso dei prodotti
		 */
		function _fillProgressBars(progressBarClass){
			//valore in cui si trova il punto 0 della barra (immagine)
			var halfBar = -417;

			$(progressBarClass).each(function(i) {
			    var unit = $(this).data('unit');
			    var progress = $(this).data('progress');
			    var total = $(this).data('total');

			    var barSize = $(this).width();
			    var progress = (progress/total)*barSize;
			    progress = progress > barSize ? barSize : progress;

			    
			    $(this).css('background-position-x', (halfBar + progress) + 'px');
			});
		}
		/*
		 * Funzione che centra il cuore per esprimere la preferenza
		 */
		function _adjustOverlays(){
			$('.heart-overlay').each(function(i){
				var container = $(this).parent().find('img');
				var totalVerticalMargin = container.height() - $(this).height();

				var margins = totalVerticalMargin/2 + 'px ' +
					0 + 'px ' + 
					totalVerticalMargin/2 + 'px ' +
					0 + 'px ';
				
				$(this).css('width',container.width());
				$(this).css('margin',margins);


			});
		}

		function _initWallLayout(){
			new AnimOnScroll( document.getElementById('producer-wall'), {
				minDuration : 0.4,
				maxDuration : 0.7,
				viewportFactor : 0.2
			} );
		}

		function _initCarousel(){
			$("#producer-carousel").owlCarousel({
				animateOut: 'slideOutDown',
			    animateIn: 'lightSpeedIn',
			    items:1,
			    margin:30,
			    smartSpeed:250,
			    nav: true,
			    dots: true,
			    loop: true,
			    lazyLoad:true,
			    navText :[
			    "<i class = 'foowd-icons foowd-icon-arrow-left'></i>",
			    "<i class = 'foowd-icons foowd-icon-arrow-right'></i>"
			    ]
			});
			$(document).trigger('producer-carusel-loaded');
		}

	   /*
		* GESTIONE EVENTI ------------------------------------------------------------------------
		*/

		//i template sono stati caricati, ora posso effettuare operazioni su di loro senza alcun problema
		$(document).on('producer-wall-loaded',function(){
			//solo ora che ho renderizzato tutti gli elementi applicao il layout
			_initWallLayout();
			//riempio le barre di probresso dei prodotti
			_fillProgressBars(postProgressBarClass);
		});

		//questo evento viene dal plugin che aggiusta il wall nelle colonne desiderate
		//significa che tutti i post sono stati caricati
		$(producerWallContainer).on('images-loaded',function(){
			_adjustOverlays();
		});
	   /* Export ----------- */
	   	window.addPreference = WallController.addPreference;
	   /*
		* METODI PUBBLICI ------------------------------------------------------------------------
		*/

		return{
			init: 			_stateCheck,
			addPreference: 	addPreference,
		};

	})();

	return ProducerController;

});