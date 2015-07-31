<?php

ob_start();

// elgg_unregister_menu_item('topbar', 'administration');

?>

<!--

classi elgg:

	elgg-body , mi permette di rendere il box al 100% del rimanente, nonostante sia preceduto da un div con float:left
	pll , lascia un paddin sinistro di 20 px

-->

<div class="elgg-body foowd-panel-container">


<?php 

$user = elgg_get_logged_in_user_entity();



if($user->Genre !== "offerente") goto salto;


echo '<p class="pll">Salve '.$user->username.',<br/> scegli cosa vorresti fare:</p>';
?>
<div id="box">

<div>
<?php
echo '<h3>Crea</h3>';
echo '<p>per creare una nuova offerta basta cliccare qui sotto.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => \Uoowd\Param::page()->add,
	    'text' => elgg_echo('Crea Nuova'),
	    'class' => 'elgg-button',
    ));
?>
</div>

<div>
<?php
echo '<h3>Visualizza Tutte</h3>';
echo '<p>cliccando potrai visualizzare una pagina con l\'elenco delle tue offerte, ed eventualmente modifcarle.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => \Uoowd\Param::page()->all,
	    'text' => elgg_echo('Mie Offerte'),
	    'class' => 'elgg-button',
    ));
?>

</div>

<?php
salto:
?>

<div>
<?php
echo '<h3>Vedi Profilo</h3>';
echo '<p>visualizza e modifica le informazioni relative al tuo profilo.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => \Uoowd\Param::page()->profile,
	    'text' => elgg_echo('Profilo'),
	    'class' => 'elgg-button',
    ));
?>
</div>

</div>
</div><!-- end foowd-panel-container -->
<?php

$body = ob_get_contents();
ob_end_clean();

echo elgg_view_page($title, '<div class="foowd-page-panel">'.$body.'</div>');

?>