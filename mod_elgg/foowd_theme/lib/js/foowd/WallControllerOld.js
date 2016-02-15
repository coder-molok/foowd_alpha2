/**
 * COPIA DI BACKUP: precedente ad cambiamento totale di Simone Scardoni
 */

define(function(require){

	/*
	 * DIPENDEZE MODULO ------------------------------------------------------------------------
     */

	var API = require('FoowdAPI');
	var Navbar = require('NavbarController');
	var templates = require('templates');
	var utils = require('Utils');
	var $ = require('jquery');
	var loadingOverlay = require('jquery-loading-overlay');

	var WallController = (function(){

		// chiavi id post e valori gli oggetti post delle api
		// array che usero' in futuro per cache... si occupa di tenere tutti i post caricati
		// o che man mano verranno caricati, in modo da ottimizzare le risorse, in futuro
		var offersPreparedCache = [] ; 


	   /*
		* IMPOSTAZIONI MODULO ------------------------------------------------------------------------
		*/
		var wallId = "#wall";
		var searchBox = "#searchText";
		var group = false;
		var postProgressBarClass = ".mini-progress";
		// evento che viene triggerato da NavbarSearch per aggiornare il wall
		var searchUpdateWallEvent = "foowd:search:update:wall:event";
		var animOnScroll = null;
		
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
			_applyColor();
			searchProducts();
		}
		
	    
		
		function searchProducts(){
			$("#wall-container").loadingOverlay();
			var userId = utils.getUserId();
			if(userId!=null && group){
				_getWallProductsGroup(userId,_getSearchText());
			}else{
				_getWallProducts(userId,_getSearchText());
			}
			
		}
		
		
		/*
		 * Funzione che riempie il wall con i prododtti del database
		 */
		function _getWallProducts(userId,search){
			
			// se ho gia' dei post vuol dire che ho svolto il prima caricamento, pertanto o sto filtrando una search o sto switchando le modalita'
			if($('[data-product-id]').length > 0){
				_updateWall();
				$(document).trigger('wall-products-loaded');
				return;
			}

			// di default cerco anche le amicizie, in modo da recuperare in una volta sola la modalita' gruppo
			API.getFriend(userId).then(function(data){
				if(data.result && data.result.friends){
					 var friendsStr = data.result.friends.join();
					 var externalIds = userId+','+friendsStr ;
				}else{
					var externalIds = userId;
				}

				// recupero tutti i dati... da raffinare!
				API.getProducts(externalIds,search).then(function(data){
					$("#wall-container").loadingOverlay('remove');

					//parso il JSON dei dati ricevuti
					var rawProducts = data.body;
					//utilizo il template sui dati che ho ottenuto
						if(rawProducts.length > 0){
							//utilizo il template sui dati che ho ottenuto
							var app = _applyProductContext(rawProducts);
							// se ho dei nuovi elementi, aggiorno il wall
							if(app.reload){
								_fillWall(app.parsedProducts);
							}
							// $(document).trigger('wall-products-loaded');
						}else{
							$(document).trigger('failedSearch');
						}
					_applyColor();
					},function(error){
					$(wallId).loadingOverlay('remove');
				console.log(error);
				});
			});
		}
		
		function _getWallProductsGroup(userId,search){
			// visto che il wall e' rigenerato ogni volta, sfrutto il DOM per svolgere lo switch
			_updateWall();
			$(document).trigger('wall-products-loaded');
		}

		/*
		 * Funzione che applica il template ripetutamente ai dati di contesto
		 */
		function _applyProductContext(context){
			// var result = "";
			var result = [];
			var reload = false;
			var userId = utils.getUserId();
			context.map(function(el) {
				// se e' gia' nella cache, evito di  prepararlo, altrimenti svolgo sopra i conti
				if(typeof offersPreparedCache[el.offer.Id] == 'undefined'){
					// conteggio per ciascuna offerta le sue quantita'
					el = utils.offerPrepare(el);
					offersPreparedCache[el.offer.Id] = el;
					// l'elemento non esisteva, pertanto devo rigenerare tutto
					reload = true;
				}else{
					el = offersPreparedCache[el.offer.Id];
				}
				// assegno l'opportuna quantita' in base al gruppo
				el.offer.totalQt = (group) ? el.offer.totalQtGroup : el.offer.totalQtUser ;
				// result += templates.productPost(el.offer);
				// ritorno gli oggetti jquery, perche' cosi' posso aggiornare tutto direttamente tramite mansonry
				el.$self = $( templates.productPost(el.offer) );
				result.push(el);
			});

			return {"parsedProducts": result, "reload": reload};
		}

		/*
		 * Funzione che riempe il tag html con i template dei prodotti complilati
		 */
		function _fillWall(content) {
			var fill = '';
			$.each(content, function(idx, el){ fill += templates.productPost(el.offer); });
			$(wallId).html(fill);
			//.addClass('animated bounceInLeft'); //animazione
		  	_initWallLayout();
		  	_fillProgressBars(postProgressBarClass);
		}

	   /*
		* Funzione che riempe il tag html con i template dei prodotti complilati
		*/
		function _getSearchText() {
			return $(searchBox).val();
		}

		function _getProducerInfo(producerId){
			API.getUserDetails(producer.producerId).then(function(data){
				
				var userData = data.body;
				
				
			
			}, function(error){	
				console.log(error);
			});
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
					//TODO metterci search
					searchProducts();
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
		* Funzione che centra il cuore per esprimere la preferenza: viene ad essere realizzata quando si carica l'immagine:
		* trigger image-loaded di AnimOnScroll
		*/
		// function _adjustOverlays(){
		// 	$('.heart-overlay').each(function(i){
		// 		var container = $(this).parent().find('img');
		// 		var totalVerticalMargin = container.height() - $(this).height();

		// 		var margins = totalVerticalMargin/2 + 'px ' +
		// 			0 + 'px ' + 
		// 			totalVerticalMargin/2 + 'px ' +
		// 			0 + 'px ';
				
		// 		$(this).css('width',container.width());
		// 		$(this).css('margin',margins);

		// 		$(this).parent().parent().css('width', container.width());
		// 	});
		// }

	   /*
		* Setta il layout impostato ai post
		*/
		function _initWallLayout(cachedPosts){

		
			// l'oggetto lo istanzio solo alla prima creazione, poi dovro' solo fare degli pudate di layout		
				// ricreato per lo piu sviluppando proprieta' masonry
				animOnScroll = new AnimOnScroll( document.getElementById('wall'), {
					minDuration : 0.4,
					maxDuration : 0.7,
					viewportFactor : 0.2,
					resize: true,
					transitionDuration: '0.4s'
				} );
	
			// animOnScroll.update([]);				
			// se faccio un update, parto dal presupposto di aver gia' inserito gli elementi in masonry
			// animOnScroll.update(cachedPosts);

		}

		
        function go2ProducerSite(producerId,event){
			var producer = utils.getUrlArgs();
			API.getUserDetailsSync(producerId).then(function(data){
				
				var userData = data.body;
				var win = window.open('http://'+data.body.Site);
			}, function(error){	
				console.log(error);
			});
			event.preventDefault();
		}

		function _applyColor(){
				/*$( "#logo" ).each(function() {
					$(this).toggleClass('logo-green',group);
					$(this).toggleClass('logo',!group);

				});*/
		}

		function toggleGroup(){
			group=!group;
			$('#groupBtn').toggleClass('foowd-icon-group-white',group);
			$('#groupBtn').toggleClass('foowd-icon-group',!group);
			$('#groupBtn').toggleClass('fw-menu-icon-group',group);
			$('#groupBtn').toggleClass('fw-menu-icon',!group);
			_applyColor();
			searchProducts();			
		}

		/**
		 * se sono gia' presenti tutti i post, allora posso semplicemente fare un update dei dati,
		 * altrimenti rigenero il wall
		 * Se ritorna true, previene la generazione del wall mediante ricaricamento
		 * @param  {[type]} rawProducts [description]
		 * @return {[type]}             [description]
		 */
		function _updateWall(){
			// controllo rispetto alla cache: se era in cache la aggiorno, altrimenti devo rigenerare il wall perche' non era presente
			$(document).find('[data-product-id]').each(function(){
				var id = $(this).attr('data-product-id');
				var tmp = offersPreparedCache[id].offer;
				var qt = (group) ? tmp.totalQtGroup : tmp.totalQtUser;
				$(this).find(postProgressBarClass).data('progress', qt).css({'transition': 'background-position 1s ease-out'});//	transition: background-position 1s ease-out; 
			});
			$("#wall-container").loadingOverlay('remove');
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
			// $(document).on('successSearch', function(e){
			// 	_initWallLayout();
			// });
			//notifica errore nel caso la ricerca testuale non ha prodotto risultati
			// $(document).on('failedSearch', function(e){
			// 	$('#foowd-error').text('La tua ricerca non ha prodotto risultati');
			// 	$('#foowd-error').fadeIn(500).delay(3000).fadeOut(500);
			// });
		});

		// se svolgo una ricerca, navbarsearch si occupa di passare il contenuto che serve a questa 
		$(document).on(searchUpdateWallEvent, function(e){
			var resp = e.FoowdNavbarSearch;
			var tags = resp.tagsObject;

			// qui dovrei implementare la chiamata API per ottenere dati aggiornati
			// ma ora non la uso perche' tanto il wall lo carico in una botta... naturalmente e' da aggiornare

			var re = new RegExp(tags.join('|'), "gi");

			var tempPost = [];

			// uso la cache 
			for(var i in offersPreparedCache){
				var post = offersPreparedCache[i].offer;
				// stringa univoca che controlla i match
				var str = post.Name + post.Description + post.Tag ;
				if(str.match(re)) tempPost.push(offersPreparedCache[i]);

			}

			// console.log(tempPost);

			$('.grid').fadeOut(function(){
				var parsedProducts = _applyProductContext(tempPost).parsedProducts;
				_fillWall(parsedProducts);
				// $(document).trigger('wall-products-loaded');
				$(this).fadeIn();

			})

		});

		//CORE MODIFIED:
		//questo evento viene dal plugin che aggiusta il wall nelle colonne desiderate
		//significa che tutti i post sono stati caricati
		// $(wallId).on('images-loaded',function(){
		// 	_adjustOverlays();
		// });
		

		
	   /* Export---------------- */
	   	window.addPreference = _addPreference;
	   	window.toggleGroup = toggleGroup;
	   		   	window.go2ProducerSite = go2ProducerSite;

	   /*
		* METODI PUBBLICI ------------------------------------------------------------------------
		*/

		return{
			init           : _stateCheck,
			addPreference  : addPreference

		};

	})();

	return WallController;

});