/**
 * animOnScroll.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 *
 * Versione dello script modificata al fine di concedere maggiore interazione con Masonry
 */
;( function( window ) {
	
	'use strict';
	
	var docElem = window.document.documentElement;

	/* ritorna la concreta altezza a disposizione */
	function getViewportH() {
		var client = docElem['clientHeight'], //  the viewable height of an element in pixels, including padding, but not the border, scrollbar or margin.
			inner = window['innerHeight']; // altezza interna della finestra, ovvero senza tollbars/scrollbars (height of the browser window's viewpor)
		if( client < inner )
			return inner;
		else
			return client;
	}

	function scrollY() {
		// pagYoffset: the pixels the current document has been scrolled from the upper left corner of the window, horizontally and vertically.
		// scrollTop: sets or returns the number of pixels an element's content is scrolled vertically
		var y = window.pageYOffset || docElem.scrollTop;
		return window.pageYOffset || docElem.scrollTop;
	}

	// http://stackoverflow.com/a/5598797/989439
	// con questo controlla l'offset Totale: 
	function getOffset( el ) {
		var offsetTop = 0, offsetLeft = 0;
		do {
			if ( !isNaN( el.offsetTop ) ) {
				offsetTop += el.offsetTop;
			}
			if ( !isNaN( el.offsetLeft ) ) {
				offsetLeft += el.offsetLeft;
			}
		} while( el = el.offsetParent )

		// console.log({
		// 	top : offsetTop,
		// 	left : offsetLeft
		// });

		return {
			top : offsetTop,
			left : offsetLeft
		}
	}

	// per ciascun elemento controllo se e' nella viewport o meno (ovvero se e' o e' stato nella zona visibile);
	// se lo e' ritorna true
	function inViewport( el, h ) {
		var elH = el.offsetHeight,
			scrolled = scrollY(),
			viewed = scrolled + getViewportH(),
			elTop = getOffset(el).top,
			elBottom = elTop + elH,
			// if 0, the element is considered in the viewport as soon as it enters.
			// if 1, the element is considered in the viewport only when it's fully inside
			// value in percentage (1 >= h >= 0)
			h = h || 0;

		return (elTop + elH * h) <= viewed && (elBottom - elH * h) >= scrolled;
	}

	function extend( a, b ) {
		for( var key in b ) { 
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	function AnimOnScroll( el, options ) {	
		this.el = el;
		this.options = extend( this.defaults, options );
		var start = this._init(true);
		for(var i in start) this[i] = start[i];
	}

	// IE Fallback for array prototype slice
	if(navigator.appVersion.indexOf('MSIE 8') > 0) {
	    var _slice = Array.prototype.slice;
	    Array.prototype.slice = function() {
	      if(this instanceof Array) {
	        return _slice.apply(this, arguments);
	      } else {
	        var result = [];
	        var start = (arguments.length >= 1) ? arguments[0] : 0;
	        var end = (arguments.length >= 2) ? arguments[1] : this.length;
	        for(var i=start; i<end; i++) {
	          result.push(this[i]);
	        }
	        return result;
	      }
	    };
	  }

	AnimOnScroll.prototype = {
		defaults : {
			// Minimum and a maximum duration of the animation (random value is chosen)
			minDuration : 0,
			maxDuration : 0,
			// The viewportFactor defines how much of the appearing item has to be visible in order to trigger the animation
			// if we'd use a value of 0, this would mean that it would add the animation class as soon as the item is in the viewport. 
			// If we were to use the value of 1, the animation would only be triggered when we see all of the item in the viewport (100% of it)
			viewportFactor : 0,
		},
		// first time prepare all functions and listeners
		_init : function() {
			this.create = true;
			this.items = Array.prototype.slice.call( document.querySelectorAll( '#' + this.el.id + ' > li' ) );
			this.itemsCount = this.items.length;
			this.itemsRenderedCount = 0;
			this.didScroll = false; // parametro utilizzato nello scroll per controllarlo: funge da semaforo per non sovrastare gli scroll

			$.bridget('masonry', Masonry);
			var self = this;

			if( !this.create ) return;

			this.create =! this.create; 

			// $(self.el).masonry('destroy');
			self.$grid = $(self.el).masonry({
				itemSelector: 'li',
				transitionDuration : 0,
				isFitWidth : true,
				resize: true,
				initLayout: false,
			} );

			imagesLoaded( this.el, function() {
				// avendo caricato come dipendenza jquery-bridget, riesco anche con requirejs a utilizzare masonry come plugin jquery. Lo faccio per comodita'
				// utilizzo masonry appendendolo all'oggetto giglia
				// console.log()
				
				self.$grid.masonry('layout');
				
				// self.$grid = $(self.el);
				// self.masonry = new Masonry(self.el, {
				// 	itemSelector: 'li',
				// 	transitionDuration : '0.4s',
				// 	isFitWidth : true,
				// 	fitWidth: true,
				// 	resize: true
				// } );
				
	
				if( Modernizr.cssanimations ) {
					// the items already shown...
					self.items.forEach( function( el, i ) {
						if( inViewport( el ) ) {
							self._checkTotalRendered();
							classie.add( el, 'shown' );
						}
					} );

					// animate on scroll the items inside the viewport
					window.addEventListener( 'scroll', function() {
						self._onScrollFn();
					}, false );
					window.addEventListener( 'resize', function() {
						self._resizeHandler();
					}, false );
				}

				self._foowdEvent();
				
			});
			// per accedere ai suoi elementi... un po forzato
			return {
				'removeElement': this.removeElement,
				'appendElement': this.appendElement,
				'prependElement': this.prependElement,
				'update' : this._update,
				'$grid' : this.$grid
			};

		},
		_foowdEvent : function(){
				// predo --------------------------
				//custom event to see when images are loaded
				var event;
				var self = this;

			 	if (document.createEvent) {
				    event = document.createEvent("HTMLEvents");
				    event.initEvent("images-loaded", true, true);
				} else {
				    event = document.createEventObject();
				    event.eventType = "images-loaded";
				} 

				event.eventName = "images-loaded";

				if (document.createEvent) {
					self.event = event;
				    self.el.dispatchEvent(event);
				} else {
				    self.el.fireEvent("on" + event.eventType, event);
				}
				//predo ---------------------------
		},
		// call this when manage items. items in questo caso e' un array di elementi che voglio visualizzare. Se e' vuoto, allora visualizzo tutta la griglia
		_update : function(){
			// aggiorno per i check che svolge il plugin al fine di applicare gli effetti
			this.items = Array.prototype.slice.call( document.querySelectorAll( '#' + this.el.id + ' > li' ) );
			this.itemsCount = this.items.length;
			this.itemsRenderedCount = 0;
			this.didScroll = false; // parametro utilizzato nello scroll per controllarlo: funge da semaforo per non sovrastare gli scroll
			
			var self = this;
			
			imagesLoaded( this.el, function() {

				// $elements.each(function(){
				// 	self.$grid.prepend($(this)).masonry('prepended', $(this));
				// })
				// console.log(self.$grid.masonry('getItemElements', $blockSelectors));
				// self.$grid.masonry('layout');
				
				if( Modernizr.cssanimations ) {
					// the items already shown...
					self.items.forEach( function( el, i ) {
						if( inViewport( el ) ) {
							self._checkTotalRendered();
							classie.add( el, 'shown' );
						}
					} );
				}
				self._foowdEvent();
			});
		},
		removeElement : function(Jel){
				// se sto provando a eliminare qualcosa che non esiste
				if(this.el.length == 0) return;
				this.masonry.remove( elements );
				this.update([]);
		},
		appendElement : function(Jel){
				// se sto provando a eliminare qualcosa che non esiste
				if(this.el.length == 0) return;
				this.$grid.append(Jel);
				var self = this;
				imagesLoaded( this.el, function() {
					self.$grid.masonry('appended', Jel ).masonry('layout');
					self.update();
				});

		},
		prependElement : function(Jel){
				// se sto provando a eliminare qualcosa che non esiste
				if(this.el.length == 0) return;
				this.$grid.prepend(Jel).masonry('prepended', Jel );
				this.update([]);
		},
		_onScrollFn : function() {
			var self = this;
			if( !this.didScroll ) {
				this.didScroll = true; // garantisco che avvenga una sola di queste
				setTimeout( function() { self._scrollPage(); }, 60 );
			}
		},
		_scrollPage : function() {
			var self = this;

			// per ogni elemento controllo se e' nella viewport o meno, eseguendo eventuali animazioni
			this.items.forEach( function( el, i ) {
				if( !classie.has( el, 'shown' ) && !classie.has( el, 'animate' ) && inViewport( el, self.options.viewportFactor ) ) {
					setTimeout( function() {
						var perspY = scrollY() + getViewportH() / 2;
						self.el.style.WebkitPerspectiveOrigin = '50% ' + perspY + 'px';
						self.el.style.MozPerspectiveOrigin = '50% ' + perspY + 'px';
						self.el.style.perspectiveOrigin = '50% ' + perspY + 'px';

						self._checkTotalRendered();

						if( self.options.minDuration && self.options.maxDuration ) {
							var randDuration = ( Math.random() * ( self.options.maxDuration - self.options.minDuration ) + self.options.minDuration ) + 's';
							el.style.WebkitAnimationDuration = randDuration;
							el.style.MozAnimationDuration = randDuration;
							el.style.animationDuration = randDuration;
						}
						
						classie.add( el, 'animate' );
					}, 25 );
				}
			});
			// riattivo la possibilita' di realizzare nuovamente questa funzione
			this.didScroll = false;
		},
		_resizeHandler : function() {
			var self = this;
			function delayed() {
				self._scrollPage();
				self.resizeTimeout = null;
			}
			if ( this.resizeTimeout ) {
				clearTimeout( this.resizeTimeout );
			}
			this.resizeTimeout = setTimeout( delayed, 1000 );
		},
		_checkTotalRendered : function() {
			++this.itemsRenderedCount;
			if( this.itemsRenderedCount === this.itemsCount ) {
				window.removeEventListener( 'scroll', this._onScrollFn );
			}
		}
	}

	// add to global namespace
	window.AnimOnScroll = AnimOnScroll;

} )( window );