<!--

classi elgg:

	elgg-body , mi permette di rendere il box al 100% del rimanente, nonostante sia preceduto da un div con float:left
	pll , lascia un paddin sinistro di 20 px

-->

<div class="elgg-body foowd-profile-container">


<?php 

$user = elgg_get_logged_in_user_entity();



if($user->Genre !== "offerente") goto salto;


echo '<p class="pll">Scegli cosa vorresti fare:</p>';
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
	    'class' => 'elgg-button elgg-button-delete',
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
	    'class' => 'elgg-button elgg-button-delete',
    ));
?>
</div>

</div>
<?php
salto:

?>
</div>