define(function(require){

	var utils = require('Utils');
	var templates = require('templates');
	var classie = require('classie');

	var NavbarController = (function(){

		var navbarContainer = ".foowd-navbar";
		var userId = null;
		var ov = {};

		function _stateCheck(search){
			switch(document.readyState){
				case "loading":
					document.onreadystatechange = function (){
						_stateCheck();
					}
				break;
				case "interactive":
				case "complete": 
					_init(search);
				break;
			}
		}

		function _init(search){
			
			userId = utils.getUserId();
            search = utils.isValid(search) ? search : false;
            var context = {
		        "search" : search,
		    };
            //carico il template della barra di navigazione
            $(navbarContainer).each(function(i,el){
            	context.logged= utils.isUserLogged();
            	if(classie.hasClass(el, 'reverse')){
            		if(utils.isValid(context.regular)){
            			delete context.regular;
            		}
            		context.reverse = true;
            	}else{
            		if(utils.isValid(context.reverse)){
            			delete context.reverse;
            		}
            		context.regular = true;
            	}
            	$(el).html(templates.navbar(context));
            	// dopo averlo caricato, posso appendergli gli eventi
            	manageSearchText();
            });
            //carico l'overlay sul menu
            _loadOverlay();
            //metto acluni listener per gli eventi di aggiunta delle preferenze
			//notifica positiva nel caso la preferenza è stata aggiunta correttamente
			$(document).on('preferenceAdded', function(e){
				$('#foowd-success').text('La tua preferenza è stata aggiunta');
				$('#foowd-success').fadeIn(500).delay(3000).fadeOut(500);
			});
			//notifica di errore nel caso la preferenza non fosse stata aggiunta
			$(document).on('preferenceError', function(e){
				$('#foowd-error').text("C'è stato un errore durante l'aggiuta della tua preferenza");
				$('#foowd-error').fadeIn(500).delay(3000).fadeOut(500);
			});
        }

        function _loadOverlay(){
        	// variabili per l'overlay button

        	var trigger = document.getElementById('trigger-overlay');
        	var overlaySection = document.querySelector('div.overlay');
        	var closeBtn = document.getElementById('close-overlay');
        
        	var transEndEventNames = {
						'WebkitTransition': 'webkitTransitionEnd',
						'MozTransition'   : 'transitionend',
						'OTransition'     : 'oTransitionEnd',
						'msTransition'    : 'MSTransitionEnd',
						'transition'      : 'transitionend',
			};
            
            ov = {
	            	triggerBttn 	   : trigger,
					overlay 		   : overlaySection,
					closeBttn 		   : closeBtn,
					transEndEventName : transEndEventNames[ Modernizr.prefixed( 'transition' ) ],
					support : { 
						transitions : Modernizr.csstransitions 
					}
			};

			//aggungo i listener al bottone della barra
            ov.triggerBttn.addEventListener( 'click', _toggleOverlay );
            ov.closeBttn.addEventListener( 'click', _toggleOverlay );
        }

        function _toggleOverlay() {
			if( classie.has( ov.overlay, 'open' ) ) {
				classie.remove( ov.overlay, 'open' );
				classie.add( ov.overlay, 'close' );
				var onEndTransitionFn = function( ev ) {
					if( ov.support.transitions ) {
						if( ev.propertyName !== 'visibility' ) return;
						this.removeEventListener( ov.transEndEventName, onEndTransitionFn );
					}
					classie.remove( ov.overlay, 'close' );
				};
				if( ov.support.transitions ) {
					ov.overlay.addEventListener( ov.transEndEventName, onEndTransitionFn );
				}
				else {
					onEndTransitionFn();
				}
			}
			else if( !classie.has( ov.overlay, 'close' ) ) {
				classie.add( ov.overlay, 'open' );
			}
		}

		function goToUserProfile(){
        	if(utils.isValid(userId)){
        		utils.goTo("profile");
        	}else{
        		utils.goTo("login");
        	}
        }

        /**
         * funzione che realizza l'effetto sul campo di ricerca:
         * rimpiazza il concetto di campo input, perche' su tal tag non e' possibile inserire elementi html, ma solo testuali
         * @return {[type]} [description]
         */
        // se il plugin viene caricato piu volte, c'e' il rischio che gli eventi $(document).on si accumulino, ripetendosi piu volte per singola pressione
        __countManageSearch = 0;
        function manageSearchText(){
        	if(__countManageSearch >0) return;
        	__countManageSearch++;
        	// mi serve perche' da esso rimuovo la classe "pulsate" per l'effetto sull'underscore
        	var $box = $('.foowd-brand');
        	// scritta foowd_ : triggera anche il click per andare alla homepage
        	var $pre = $('.foowd-brand-pre-search');
        	// campo search
        	var $search = $('#searchText').first();
        	var tags = '[data-tag]';
        	var pulsationSpan = '<span class="foowd-pulsate">_</span>';

        	$(document).on('keydown', function(e){
        		var code = (e.keyCode) ? e.keyCode : e.which ;
        		// 8 e' il codice del backspace: devo impedire che avvenga il back della history del browser
        		// 32 e' il codice dello space: quando lo si clicca avviene lo scroll, che non serve
        		if( code == 8 || code == 32 ) e.preventDefault() ;
        	});

        	var _newTag = 0;
        	$(document).on('keyup', function(e){
                // prova...
                $('.product-post').css({'visibility': 'hidden'});

        		// rimuovo la classe per poi appenderla all'ultimo underscore
        		$box.find('.foowd-pulsate').removeClass('foowd-pulsate');
        		$(tags).css({'background': 'transparent'});
        		// valore attuale: elimino gli uderscore che inserisco alla fine
        		var actual = $search.text().replace(/(^_|_$)/g, '');
        		// valore rilasciato
        		var code = (e.keyCode) ? e.keyCode : e.which ;
        		var c = String.fromCharCode(code);
        		// se e' il backspace allora cancello!
        		
        		actual = (code==8) ? actual.slice(0,-1) : actual + c.toLowerCase() ;

        		// se il campo e' vuoto, allora l'underscore di foowd_ deve lampeggiare
        		if(actual == ''){
        			$pre.html(  $pre.text().replace(/_/, pulsationSpan) )
                    $('.product-post').css({'visibility': 'visible'});
                }
                else{
                    if(actual.length < 3) $('.product-post').css({'visibility': 'visible'});
        			// rimpiazzo gli spazi con un underscore ed eseguo un trim degli underscore
        			if(_newTag != 0){
        				actual=actual.replace(_newTag, _newTag + '_');
        				actual = actual.replace(/ /g, '');
        				_newTag = 0;
        			}
        			if(actual.match(/ $/)){
        				_newTag = actual.replace(/ $/,'');	
        			}
        			actual = actual.replace(/(_+|,|\.|;|'|")+/g, '_');
        			actual = actual.split('_');
        			var tmpstr = ''
        			for(var i in actual){
        				var random = "#"+((1<<24)*Math.random()|0).toString(16);
        				var myunder = '_';        				
        				if( i == actual.length -1 ) myunder =  pulsationSpan;
        				tmpstr = tmpstr + '<span style="color:' + random + ';">' + actual[i].replace(/ /,'') + myunder + '</span>'
        				// ora appendo nen body:
                        if(actual[i].length > 2){
                            (function(c){
            				    $('[data-tag*="'+actual[i]+'"]').css({'background-color': c});
                                $('[data-tag*="'+actual[i]+'"]').closest('.product-post').css({'visibility': 'visible'});
                                return;
                            })(random);
                        }
        			}
        			actual = tmpstr
        		}
        		$search.html(actual)
        	});
        }

		return{
			loadNavbar : 	 _stateCheck,
			goToUserProfile: goToUserProfile,
		};
	})();
	window.NavbarController = NavbarController;
	return NavbarController;
});
