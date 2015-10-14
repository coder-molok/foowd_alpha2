define(function(require){

	/*
	 * DIPENDEZE MODULO ------------------------------------------------------------------------
     */

	var API = require('FoowdAPI');
	var Navbar = require('NavbarController');
	var templates = require('templates');
	var utils = require('Utils');
	var $ = require('jquery');

	var WallController = (function(){

	   /*
		* IMPOSTAZIONI MODULO ------------------------------------------------------------------------
		*/
		var wallId = "#wall";
		var searchBox = "#searchText";
		var postProgressBarClass = ".mini-progress";
		
		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "0",
   		};
   	   
   	   /*
		* FUNZIONI PRIVATE DEL MODULO -----------------------------------------------------------
		*/

		//nel controller devo essere sicuro che il dom sia stato caricato correttamente
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
			//carico l'header 
			Navbar.loadNavbar(true);
			//carico il wall con i template
			_getWallProducts();
		}
		
		/*
		 * Funzione che riempie il wall con i prododtti del database
		 */
		function _getWallProducts(){
			var userId = utils.getUserId();
			API.getProducts(userId).then(function(data){
				//parso il JSON dei dati ricevuti
				var rawProducts = data.body;
				//utilizo il template sui dati che ho ottenuto
				var parsedProducts = _applyProductContext(rawProducts);
				//riempio il wall con i prodotti 
				_fillWall(parsedProducts);
				$(document).trigger('wall-products-loaded');
			},function(error){
				console.log(error);
			});
		}

		/*
		 * Funzione che applica il template ripetutamente ai dati di contesto
		 */
		function _applyProductContext(context) {
			var result = "";
			var userId = utils.getUserId();
			context.map(function(el) {
				//aggiungo l'immmagine
				el = utils.addPicture(el, utils.randomPictureSize(el.Id));
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
		function _fillWall(content) {
			$(wallId)
		  	    .html(content);
				//.addClass('animated bounceInLeft'); //animazione
		}

	   /*
		* Funzione che riempe il tag html con i template dei prodotti complilati
		*/
		function _getSearchText() {
			return $(searchBox).val();
		}

	   /*
		* Ricerca dei prodotti in base alla chiave testuale
		*/
		function _searchProducts(e){
			if(e.keyCode == 13){
				var textSearch = _getSearchText();
				var userId = utils.getUserId();
				API.getProducts(userId, textSearch).then(function(data){
					//parso il JSON dei dati ricevuti
					var rawProducts = data;
	              	//prendo l'id dell'utente (se loggato) e vedo che template usare
					if(rawProducts.body.length > 0){
						//utilizo il template sui dati che ho ottenuto
						var parsedProducts = _applyProductContext(rawProducts.body);
						//riempio il wall con i prodotti 
						_fillWall(parsedProducts);
						$(document).trigger('successSearch');
					}else{
						$(document).trigger('failedSearch');
					}

				},function(error){
					console.log(error);
				});
			}
		}

	   /*
		* Funzione che aggiunge una preferenza
		*/
		function _addPreference(offerId, qt) {
			if(utils.isUserLogged()){
	    		//setto i parametri della mia preferenza
				preference.OfferId = offerId;
				preference.ExternalId = utils.getUserId();
				preference.Qt = qt;
				//richiamo l'API per settare la preferenza
				API.addPreference(preference).then(function(data){
					_getWallProducts();
					$(document).trigger('preferenceAdded');
				}, function(error){
					$(document).trigger('preferenceError');
					console.log(error);
				});
			}else{
				utils.goTo('login');
			}

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

			    
			    $(this).css('background-position', (halfBar + progress) + 'px 0');
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

				$(this).parent().parent().css('width', container.width());
			});
		}
	   /*
		* Setta il layout impostato ai post
		*/
		function _initWallLayout(){
			new AnimOnScroll( document.getElementById('wall'), {
				minDuration : 0.4,
				maxDuration : 0.7,
				viewportFactor : 0.2
			} );
		}

	   /*
		* GESTIONE EVENTI ------------------------------------------------------------------------
		*/

		//i template sono stati caricati, ora posso effettuare operazioni su di loro senza alcun problema
		$(document).on('wall-products-loaded',function(){
			//solo ora che ho renderizzato tutti gli elementi applicao il layout
			_initWallLayout();
			//riempio le barre di probresso dei prodotti
			_fillProgressBars(postProgressBarClass);

			//attacco i listener alla barra di ricerca
			$(document).on('successSearch', function(e){
				_initWallLayout();
			});
			//notifica errore nel caso la ricerca testuale non ha prodotto risultati
			$(document).on('failedSearch', function(e){
				$('#foowd-error').text('La tua ricerca non ha prodotto risultati');
				$('#foowd-error').fadeIn(500).delay(3000).fadeOut(500);
			});
		});


		//CORE MODIFIED:
		//questo evento viene dal plugin che aggiusta il wall nelle colonne desiderate
		//significa che tutti i post sono stati caricati
		$(wallId).on('images-loaded',function(){
			_adjustOverlays();
		});
	   /* Export---------------- */
	   	window.addPreference = _addPreference;
	   	window.searchProducts = _searchProducts;
	   /*
		* METODI PUBBLICI ------------------------------------------------------------------------
		*/

		return{
			init           : _stateCheck,
			searchProducts : _searchProducts,
			addPreference  : addPreference,
		};

	})();

	return WallController;

});