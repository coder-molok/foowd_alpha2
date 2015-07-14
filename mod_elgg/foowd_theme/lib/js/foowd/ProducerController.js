define(function(require){

	//API foowd
	var API = require('FoowdAPI');
	//jQuery
	var $ = require('jquery');
	//templates handlebars
	//var templates = require('templates');
	//elgg utility
	//var elgg = require('elgg');
	
	//util library
	//var utils = require('Utils');
	//creo il controller della pagina dettaglio
	var ProducerController = (function(){

		function getProducerInfo(){
			//TODO : call the API to get producer info
			$(document).trigger('producer-info-loaded');
		}

		function initCarousel(){
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
			});
			$(document).trigger('producer-carusel-loaded');
		}

		$(document).ready(function(){
			initCarousel();
		});

		return{

		};
	})();

	return ProducerController;
});