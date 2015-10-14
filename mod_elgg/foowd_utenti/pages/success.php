<?php
// make sure only logged in users can see this page
gatekeeper();

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$title = "Dati Salvati";

// start building the main column of the page
$content = elgg_view_title($title);


//$content .= elgg_view('foowd_offerte/add', array(), $vars);
$content .= '<p>I tuoi dati sono stati modificati con successo.</p>';
$content .= '<p>Sarai reindirizzato al tuo pannello utente entro <span id="counter">5</span> secondi.</p>';
?>



<?php
$content .= '<p>Se non vuoi attendere oppure non vieni reindirizzato puoi cliccare ';
$content .= elgg_view('output/url', array(
				'href' => elgg_get_site_url() . \Uoowd\Param::page()->panel,
			    'text' => elgg_echo('qui'),
			    // 'class' => 'elgg-button elgg-button-delete',
		    ))."</p>\n\r<br/>";

// add the form stored in /views/default/forms/foowd_offerte/add.php
//$content .= elgg_view_form('foowd_offerte/add');

// optionally, add the content for the sidebar
$sidebar = "";

// layout the page one_sidebar
// $body = elgg_view_layout('one_sidebar', array(
//    'content' => $content
// ));
$body = $content;

// draw the page
echo elgg_view_page($title, $body);
?>

<script type="text/javascript">
require(['page', 'elgg'], function(page, elgg){
	// page = require('page');
	
	function countdown() {
	    var i = document.getElementById('counter');
	    i.innerHTML = parseInt(i.innerHTML)-1;
	    if (parseInt(i.innerHTML)<=0) {
	        location.href = elgg.get_site_url()+page.panel;
	    }
	}
	setInterval(function(){ countdown(); },1000);

});
</script>