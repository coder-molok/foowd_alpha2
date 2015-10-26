root = this

( (root, factory)-> 

	if typeof define is 'function' and define.amd
		# AMD. Register as an anonymous module.
		define(['elgg','jquery', 'foowdCookiePolicy'], factory);
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

	policy = require('foowdCookiePolicy')
	policy.init( {link:elgg.get_site_url()+'cookie-policy'}	)

	# Personalizzo i popup di sistema. Css in foowd-main.styl
	# Rimuovo blocco ogni animazione e tutti gli eventi sopra di lei	
	$('.elgg-system-messages li.elgg-message').finish().fadeIn(0).delay(3000).fadeOut(4000)

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