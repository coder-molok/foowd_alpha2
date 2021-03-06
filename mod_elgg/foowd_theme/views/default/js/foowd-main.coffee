root = this

( (root, factory)-> 

	if typeof define is 'function' and define.amd
		# AMD. Register as an anonymous module.
		define(['elgg','jquery', 'foowdCookiePolicy', 'page'], factory);
	else if typeof exports is 'object'
		module.exports = factory();
	else
		root.returnExports = factory();
  
)(this, 


()->
	# per triggerare i messaggi di sistema da pagine esterne
	# https://elgg.org/discussion/view/133108/system-message

	$ = require('jquery')
	elgg = require('elgg');
	page = require('page');

	policy = require('foowdCookiePolicy')
	policy.init( {
		link: elgg.get_site_url()+page.cookiePolicy,
		link2: elgg.get_site_url()+page.legalConditions
		}	)


	# sposto i messaggi di distema sotto la navbar
	navbar = $('.foowd-navbar');
	navbarBottom = navbar.offset().top + navbar.height();
	# display impostato a none in foowd-main.styl
	$('.elgg-system-messages').css({'top': navbarBottom + 'px', 'display': 'block'})

	# Personalizzo i popup di sistema. Css in foowd-main.styl
	# Rimuovo blocco ogni animazione e tutti gli eventi sopra di lei	
	$('.elgg-system-messages li.elgg-message').finish().fadeIn(0).delay(3000).fadeOut(4000)

	# se richiamo questa funzione, allora faccio sparire il popup di errore dopo 4 secondi
	root.removeSystemErrorPopup = ()->
		$('.elgg-system-messages li.elgg-state-error').finish().fadeIn(0).delay(3000).fadeOut(1000, ()-> $(this).remove() )


	root.foowdAlert = (output_msg, title_msg)->
		
		if (!title_msg)
			title_msg = 'Alert';

		if (!output_msg)
			output_msg = 'No Message to Display.';

		$("<div></div>").html(output_msg).dialog({
			title: title_msg,
			resizable: false,
			modal: true,
			buttons: {
				"Ok": () ->
					$( this ).dialog( "close" );
			}
		});

	return
);