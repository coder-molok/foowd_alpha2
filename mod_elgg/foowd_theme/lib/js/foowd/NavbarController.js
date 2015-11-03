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
			//ov.closeBttn.addEventListener( 'click', _toggleOverlay );
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

		return{
			loadNavbar : 	 _stateCheck,
			goToUserProfile: goToUserProfile,
		};
	})();
	window.NavbarController = NavbarController;
	return NavbarController;
});