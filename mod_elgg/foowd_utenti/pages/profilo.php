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

echo '<p class="pll"><h2>Salve '.$user->username.',</h2> scegli cosa fare:</p>';
?>
<div class="box">

<div>
<?php
echo '<h3>Avatar</h3>';
echo '<p>per inserire o modificare il tuo avatar.</p>';
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


__SKIP_NOT_OFFER:
?>

<div>
<?php
echo '<h3>Impostazioni</h3>';
echo '<p>visualizza e modifica le impostazioni del profilo.</p>';
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