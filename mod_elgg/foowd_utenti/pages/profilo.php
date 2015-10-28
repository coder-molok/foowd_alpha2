<?php

// pagina accessibile solo ai loggati
elgg_gatekeeper();

ob_start();

// elgg_unregister_menu_item('topbar', 'administration');

?>

<!--

classi elgg:

	elgg-body , mi permette di rendere il box al 100% del rimanente, nonostante sia preceduto da un div con float:left
	pll , lascia un paddin sinistro di 20 px

-->

<div class="elgg-body foowd-profilo-container">


<?php 

$user = elgg_get_logged_in_user_entity();

$par['entity']=$user;

$pid = 'foowd_utenti/';

echo '<p class="pll"><h2>Salve '.$user->username.',</h2> scegli cosa vorresti fare:</p>';
?>
<div id="box">

<div>
<?php
echo '<h3>Modifica Avatar</h3>';
echo '<p>per aggiungere/modificare il tuo avatar.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => $pid.'avatar',
	    'text' => elgg_echo('Avatar'),
	    'class' => 'elgg-button',
    ));
?>
</div>

<?php
$genre = 'offerente';
if ($user->Genre !== $genre) goto __SKIP_NOT_OFFER;

echo '<div>';
echo '<h3>Gallery</h3>';
echo '<p>cliccando potrai visualizzare una pagina con l\'elenco delle tue offerte, ed eventualmente modifcarle.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => $pid.'gallery',
	    'text' => elgg_echo('Gallery'),
	    'class' => 'elgg-button',
    ));

echo '</div>';

?>

<div>
<?php
echo '<h3>DATI</h3>';
echo '<p>visualizza e modifica le informazioni relative al tuo profilo.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => $pid.'dati',
	    'text' => elgg_echo('I miei dati'),
	    'class' => 'elgg-button',
    ));
?>
</div>

<?php
__SKIP_NOT_OFFER:
?>

<div>
<?php
echo '<h3>Impostazioni</h3>';
echo '<p>visualizza e modifica le impostazioni del tuo profilo, come password e email.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => elgg_get_site_url().'settings/user/'.elgg_get_logged_in_user_entity()->username,
	    'text' => elgg_echo('Impostazioni'),
	    'class' => 'elgg-button',
    ));
?>
</div>


</div><!-- box -->

</div><!-- foowd-profilo-container -->
<?php

$body = ob_get_contents();
ob_end_clean();

echo elgg_view_page($title, '<div class="foowd-page-panel">'.$body.'</div>');

?>