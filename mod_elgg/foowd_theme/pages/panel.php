<?php

ob_start();

// elgg_unregister_menu_item('topbar', 'administration');


?>

<!--

classi elgg:

	elgg-body , mi permette di rendere il box al 100% del rimanente, nonostante sia preceduto da un div con float:left
	pll , lascia un paddin sinistro di 20 px


vedere foowd-utenti.styl in foowd_utenti/

-->

<div class="elgg-body foowd-panel-container">





<?php

$user = elgg_get_logged_in_user_entity();



if($user->isAdmin() || $user->Genre == 'offerente'){
$title = ($user->isAdmin() ) ? 'AMMINISTRAZIONE' : 'Pannello Produttore' ; 


?>

<h1><?php echo $title; ?></h1>


<div class="box">

<div>
<?php
echo '<h3>Prenotazioni</h3>';
echo '<p>visualizza l\'elenco delle ordinazioni ancora da chiudere.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => \Uoowd\Param::page()->purchase,
	    'text' => elgg_echo('Prenotazioni'),
	    'class' => 'elgg-button',
    ));
?>
</div>

</div><!-- end box -->

<div style="width:100%; height: 5px; background-color:pink;margin-bottom: 20px;"></div>

<?php

} // fine if se amministratore o offerente
?>






<?php 



echo '<p class="pll">Salve '.$user->username.',<br/> scegli cosa fare:</p>';
?>
<div class="box">

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
echo '<h3>Profilo</h3>';
echo '<p>visualizza e modifica le informazioni del profilo.</p>';
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
echo '<h3>Amicizie</h3>';
echo '<p>Gestisci le richieste di amicizia ricevute o inviate, e i tuoi amici attuali.</p>';
echo elgg_view('output/url', array(
		// associate to the action
		'href' => \Uoowd\Param::page()->friendsManage,
	    'text' => elgg_echo('Amicizie'),
	    'class' => 'elgg-button',
    ));
?>
</div>


</div><!-- endo box -->





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