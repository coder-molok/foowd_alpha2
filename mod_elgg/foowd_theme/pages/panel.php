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


echo '<p class="pll">Salve '.$user->username.',<br/> scegli cosa vorresti fare:</p>';
?>
<div id="box">

<?php
if($user->Genre !== "offerente") goto salto;
?>

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



<?php
// echo '<div>';
// echo '<h3>Visualizza Preferenze</h3>';
// echo '<p>cliccando potrai visualizzare l\'elenco delle tue offerte e vedere chi tra i tuoi vi ha aderito. Se vorrai potrai anche lanciare l\'ordinazione una volta raggiunta la quantita\' minima.</p>';
// echo elgg_view('output/url', array(
// 		// associate to the action
// 		'href' => \Uoowd\Param::page()->userPreferences,
// 	    'text' => elgg_echo('Preferenze'),
// 	    'class' => 'elgg-button',
//     ));
// echo '</div>';
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


<div>
<?php
echo '<h3>Gestisci Amicizie</h3>';
echo '<p>Per controllare le richieste di amicizia che hai ricevuto, per effettuarne o per eliminare amicizie.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => \Uoowd\Param::page()->friendsManage,
	    'text' => elgg_echo('Amicizie'),
	    'class' => 'elgg-button',
    ));
?>
</div>


</div>
</div><!-- end foowd-panel-container -->
<?php

// $address = "scardoni.simone@gmail.com";

// $subject = 'Test email.';

// $body = 'Elgg!';

// $header = 'From: cs@kursus-ol.com' . "\r\n" . //header email
// 'Reply-To: cs@kursus-ol.com' . "\r\n" .
// 'X-Mailer: PHP/' . phpversion();

// echo "Attempting to email $address...<br />";

// if (mail($address, $subject, $body, $header)) {
//         echo 'SUCCESS!  PHP successfully delivered email to your MTA.  If you don\'t see the email in your inbox in a few minutes, there is a problem with your MTA.';
// } else {
//         echo 'ERROR!  PHP could not deliver email to your MTA.  Check that your PHP settings are correct for your MTA and your MTA will deliver email.';
// }


$body = ob_get_contents();
ob_end_clean();

echo elgg_view_page($title, '<div class="foowd-page-panel">'.$body.'</div>');
//foowd utenti
//
//
//you have been logged in
//mail sent
//
//success
//
//
//invite friends al network