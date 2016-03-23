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
	require('jquery-foowd');

	var WallController = (function(){


	   /*
		* IMPOSTAZIONI MODULO ------------------------------------------------------------------------
		*/
		var wallId = "#wall";
		var searchBox = "#searchText";
		var group = false;
		var postProgressBarClass = ".mini-progress";
		var __animOnScroll = '';
		var __userId = utils.getUserId();
		// numero di offerte da cercare in una sola search
		var __offerOffset = 20 ;

		// ------------------------------------------------------------------------------------------
		// Per scelta le ricerche e gli aggiornamenti avvengono mediante eventi. Saranno gli eventi a
		// richiamare l'opportuna funzione e a passare i dovuti dati. TUTTI i trigger vengono fatti sul document
		 
		// evento che viene triggerato da NavbarSearch per aggiornare il wall. Parametro della ricerca: FoowdNavbarSearch
		var __searchUpdateWallEvent = "foowd:search:update:wall:event";
		
		// Ho 2 tipi di ricerche: quelle in cui rigenero il wall, e quelle in cui appendo dati.
		//
		// evento triggerato da questo plugin per far avvenire una ricerca. Parametro della ricerca: FoowdWallSearch
		var __updateSearch = "foowd:search:update"; 
		var __createSearch = "foowd:search:create";
		// prima di creare un nuovo wall per via di una search, controllo tutte le offerte che ho nella cache
		var __checkSearch = 'foowd:search:check';

		// chiavi id post e valori gli oggetti post delle api
		// array che usero' in futuro per cache... si occupa di tenere tutti i post caricati
		// o che man mano verranno caricati, in modo da ottimizzare le risorse, in futuro
		var __cache = {
			"offersPrepared": [],	// raccolta di tutte le offerte raccolte nella cache di pagina
			"searchCollection": [], // oggetto collezione dei tags ritornati tipicamente da navbarsearch
			"idxCollection" : [],	// per svolgere una ricerca incrementale in base alla paginazione: nella search equivale al "not productId"
			"searchTraffic": true, 	// per bloccare la sovrapposizione di ricerche
			"masonryComplete": true,	// per bloccare l'aggiornamento di masonry. probabilmente una promise poteva andare bene
			"actualSearch": '',			// salvo la ricerca attuale: se non cambia devo fare un semplice update della pagina, altrimenti ricarico il wall
			"oldTop": 0 ,				// decido se devo continuare a fare chiamate API o meno. Usato nello scroll
		}

		
		var preference = {
   				OfferId : "",
   				ExternalId : "",
   				type : "create",
   				Qt : "0",
   		};

   		// $(document).scrollTop(0)
   	   
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
		//inizializzazione del controller: essendo la prima volta triggero una ricerca passando all'evento un array vuoto
		function _init(){
			//carico l'header 
			Navbar.loadNavbar(true);
			// la prima volta creo il wall
			_getWallProducts(__createSearch);
		}


		// NB: in generale la creazione avviene sfruttando la cache

		// finita la ricerca, rigenero il wall: tutto
		$(document).on(__createSearch+':response', function(e){
			// console.log(e.foowdResponse.parsedProducts)
			// console.log(__cache.offersPrepared);
			 // _createWall(e.foowdResponse.parsedProducts);
			 _createWall( _takeCached().parsedProducts );
		});

		// creo da capo un wall
		_createWall =  function(content){
			$(wallId).html('');
		  	$('.grid').fadeOut(function(){
				var fill = '';
				for(var i in  content){
					fill += templates.productPost(content[i].offer);
				}			
				//.addClass('animated bounceInLeft'); //animazione
		  		$(wallId).html(fill);
		  		if(__animOnScroll != '' ) __animOnScroll.$grid.masonry('destroy');
		  		// applico la classe che richiama masonry 
		  		__animOnScroll = new AnimOnScroll( document.getElementById('wall'), {
		  			minDuration : 0.4,
		  			maxDuration : 0.7,
		  			viewportFactor : 0.2,
		  			resize: true,
		  			transitionDuration: 0
		  		} );

		  		__animOnScroll.$grid.on( 'layoutComplete', function( event, items ) {
			  		// console.log( items.length );
			  		// aspetto l'aggiornamento dell'animazione
			  		setTimeout(function(){
			  			__cache.searchTraffic = true;
			  			__cache.oldTop = $(document).scrollTop();
			  		},1000);
					_fillProgressBars(postProgressBarClass);
				});


		  		__cache.searchTraffic = true;
		  		$("#wall-container").loadingOverlay('remove');
		  		_fillProgressBars(postProgressBarClass);
		  		
		  		$(this).fadeIn();
		  	})
		}


		/** recupero quelli chached e che matchano l'attuale ricerca */
		_takeCached = function(){
			// ma ora non la uso perche' tanto il wall lo carico in una botta... naturalmente e' da aggiornare
			var re = new RegExp(__cache.actualSearch.replace(/,/g, '|'), "gi");
			var tempPost = [];
			for(var i in __cache.offersPrepared){
				var post = __cache.offersPrepared[i].offer;
				// stringa univoca che controlla i match
				var str = post.Name + post.Description + post.Tag ;
				if(str.match(re)) tempPost.push(__cache.offersPrepared[i]);
			}
			var app = _applyProductContext(tempPost);
			return app;
		}

		// NB: in generale l'update avviene sfruttando il risultato della chiamata, e appendendo a quello gia' generato dal create
		/**
		 * all'update mi limito a ad aggiungere gli elementi via masonry
		 * @param  {Array}  e){						var items         [description]
		 * @return {[type]}               [description]
		 */
		$(document).on(__updateSearch+':response', function(e){
			// se non ho passato una ricerca specifica, allora gli dico di rigenerare il wall partendo dalla cache
			var items = [] ;
			var update = [];
			// console.log(e.foowdResponse.parsedProducts)
			// console.log(__cache.offersPrepared);
			$(wallId).find('li [data-product-id]').each(function(){ items.push($(this).attr('data-product-id'));});
			for(var i in e.foowdResponse.parsedProducts){
				if($.inArray(i, items) >= 0){
					continue;	
				} 
				var el = e.foowdResponse.parsedProducts[i];
				update.push(el.$self);
			}
			update = $( $.map(update, function(el){ return el.get(0); }) );
			$("#wall-container").loadingOverlay('remove');
			if(update.length > 0){
				// blocco la cache per evitare che masonry faccia erroneamente i conti a causa di un elevato numero di elementi da aggiornare
				__animOnScroll.appendElement(update);
			}else{
				__cache.searchTraffic = true;
			}
		});
		
		
		/*
		 * Funzione che riempie il wall con i prododtti del database
		 */
		function _getWallProducts(eventType){

			// if(!userId) userId = __userId;
			// if(!search) search = '';
						
			// evito di svolgere ricerche ripetutamente
			if(!__cache.searchTraffic) return;
			// blocco il semaforo e a fine API lo sblocco
			__cache.searchTraffic = false;
			$("#wall-container").loadingOverlay();
			
			// ne cerco un tot per volta
			var query = {};
			// rimossa per la modalita' singola offerta per produttore
			// query.offset = __offerOffset;
			// console.log(__cache.actualSearch)
			// quelli gia' presenti evito di cercarli nuovamente
			// if(__cache.idxCollection.length > 0 ) query.excludeId = __cache.idxCollection;
			if(typeof search != 'undefined' && search != ''){ 
				query.search = search;
			}else if(__cache.actualSearch != ''){
				var obj = {
					"Name": __cache.actualSearch,
					"Description": __cache.actualSearch,
					"Tag": __cache.actualSearch
				}
				query.match = JSON.stringify(obj);
			}

			// gli dico di aggiungere anche gli amici
			query.withFriends = true;

			// visualizzo solo quelle non scadute:
			var now = new Date().toISOString().slice(0, 19).replace('T', ' ');
			query.Expiration = JSON.stringify({min: now.toString()});

			// recupero tutti i dati... da raffinare!
			API.getProducts(query).then(function(data){
				$("#wall-container").loadingOverlay('remove');

				//parso il JSON dei dati ricevuti
				var rawProducts = data.body;
				//utilizo il template sui dati che ho ottenuto
				var app = [];
					if(Object.keys(rawProducts).length > 0){
						//utilizo il template sui dati che ho ottenuto
						app = _applyProductContext(rawProducts);
					}
					$(document).trigger({"type": eventType + ':response', "foowdResponse": app})
				},function(error){
				$(wallId).loadingOverlay('remove');
				// essendoci un errore non ho niente da aggiornare, quindi la GUI non ha problemi
				__cache.searchTraffic = !__cache.searchTraffic;
				console.log(error);
			});
		}
		

		/*
		 * Gestione del template e conseguente aggiornamento della cache.
		 */
		function _applyProductContext(context){
			var result = [];
			// se degli elementi non sono nella cache allora dovro' ricaricarla
			var reload = false;
			var userId = utils.getUserId();
			var parsedIdx = [];
			// memorizzo l'ultimo indice per eventualmente 
			var groups = (typeof context.groups == 'undefined') ? null : context.groups ;
			var isNumeric = /^[-+]?(\d+|\d+\.\d*|\d*\.\d+)$/;
			for(var i in context){
				var el = context[i];
				if(!isNumeric.test(i)){
					// console.log(el)
					continue;	
				} 
				// se e' gia' nella cache, evito di  prepararlo, altrimenti svolgo sopra i conti
				if(typeof __cache.offersPrepared[el.offer.Id] == 'undefined'){
					// tengo conto degli indici per evitare di rieseguire delle search sugli stessi elementi quando chiamo il DB
					__cache.idxCollection.push(el.offer.Id);
					// conteggio per ciascuna offerta le sue quantita'
					el = utils.offerPrepare({el: el,groups: groups});
					__cache.offersPrepared[el.offer.Id] = el;
					// l'elemento non esisteva, pertanto devo rigenerare tutto
					reload = true;
				}else{
					el = __cache.offersPrepared[el.offer.Id];
				}
				// assegno l'opportuna quantita' in base al gruppo
				// il prezzo sul singolo post
				el.offer.totalQt = (group) ? el.offer.totalQtGroup : el.offer.totalQtUser ;
				// la progressbar sul totale per utente
				el.offer.actualProgress = (group) ? el.offer.totalPriceGroup : el.offer.totalPriceUser ;
				// if(el.offer.Id == 29) console.log(el)
				// result += templates.productPost(el.offer);
				// ritorno gli oggetti jquery, perche' cosi' posso aggiornare tutto direttamente tramite mansonry
				el.$self = $( templates.productPost(el.offer) );
				parsedIdx.push(el.offer.Id);
				result[el.offer.Id] = el;
			};

			return {"parsedProducts": result, "reload": reload, "parsedIdx": parsedIdx};
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
			    $(this).attr('data-progress', progress); 
			    var total = $(this).data('total');

			    var barSize = $(this).width();
			    var progress = (progress/total)*barSize;
			    progress = progress > barSize ? barSize : progress;

			    
			    $(this).css('background-position', (halfBar + progress) + 'px 0');
			});
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


		/**
		 * Quando viene cambiato il gruppo, l'unica cosa da fare e' aggiornare il wall, e per farlo posso semplicemente utilizzare la cache.
		 * @return {[type]} [description]
		 */
		function toggleGroup(){
			group=!group;
			$('#groupBtn').toggleClass('foowd-icon-group-white',group);
			$('#groupBtn').toggleClass('foowd-icon-group',!group);
			$('#groupBtn').toggleClass('fw-menu-icon-group',group);
			$('#groupBtn').toggleClass('fw-menu-icon',!group);
			_updateWall();
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
				var tmp = __cache.offersPrepared[id].offer;
				// var qt = (group) ? tmp.totalQtGroup : tmp.totalQtUser;
				var qt = (group) ? tmp.totalPriceGroup : tmp.totalPriceUser;
				$(this).find(postProgressBarClass).data('progress', qt).css({'transition': 'background-position 1s ease-out'});//	transition: background-position 1s ease-out; 
			});
			$("#wall-container").loadingOverlay('remove');
			_fillProgressBars(postProgressBarClass);
		}


	   /*
		* GESTIONE EVENTI ------------------------------------------------------------------------
		*/

		// se svolgo una ricerca, navbarsearch si occupa di passare il contenuto che serve a questa 
		// intercetto la richiesta della search, e faccio una chiamata alle API a prescindere dal contenuto del wall, per semplicita'
		$(document).on(__searchUpdateWallEvent, function(e){
			// console.log(e);
			var resp = e.FoowdNavbarSearch;
			var tags = resp.tagsObject;

			// scambio i risultati delle ricerche. mi serve per vedere se e' cambiato qualcosa o meno, cosi' da capire se devo fare un update o un create allo scroll
			__cache.actualSearch = tags.join();
			_getWallProducts(__checkSearch);
		});
		// il risultato di __getWallProducts e' di aver aggiornato la lista, pertanto posso generare il wall con TUTTI i dati che matchano la cache
		// che e' stata aggiornata grazie alla chiamata precedente
		$(document).on(__checkSearch+':response', function(){
			$('.grid').fadeOut(function(){
				// var app = _applyProductContext(tempPost);
				$("#wall-container").loadingOverlay();
				$(document).trigger({"type":__createSearch + ':response', "foowdResponse": _takeCached()});
				// $(this).fadeIn();gine
			});
		});


		// lo scroll INCREMENTA
		$(document).on('scroll', function(e){
			percentage =  100 * $(window).scrollTop() / ($(document).height() - $(window).height());
			// se sono in fondo, evito che continuino a verificarsi delle chiamate ad ogni scroll
			// console.log(percentage)
			if(percentage > 50 && __cache.oldTop < $(document).scrollTop() ){
					__cache.oldTop = $(document).scrollTop()
					_getWallProducts(__updateSearch);
			}

			if(percentage > 95 && __cache.oldTop < $(document).scrollTop()){
				// interrompo lo scroll fino a quando non avviene il caricamento
				if(!__cache.searchTraffic){
					$(document).scrollTop(__cache.oldTop);
					return;
				}else{
					__cache.oldTop = $(document).scrollTop()
				}
			}
		});


		// $(document).on('click', '.product-post-main-frame')


	   /* Export---------------- */
	   	// window.addPreference = _addPreference;
	   	window.toggleGroup = toggleGroup;
	   		   	window.go2ProducerSite = go2ProducerSite;

	   /*
		* METODI PUBBLICI ------------------------------------------------------------------------
		*/

		return{
			init           : _stateCheck,
			// addPreference  : addPreference
		};

	})();

	return WallController;

});

