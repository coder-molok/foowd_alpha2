<?php

// estensione della view page/elements/sidebar impostata in start.php di foowd_offerte

$Pid = 'foowd_offerte';
?>
<!-- <div class="elgg-menu-hz">Foowd Offerte</div> -->
<?php

// modo per ottenere tutti i metadata di una certa entita'
//var_dump(get_metadata_for_entity($id));

$id = elgg_get_logged_in_user_guid();
$user = get_entity($id);

if($user->Genre !== "offerente") return;

echo elgg_view('output/url', array(
		// associate to the action
		'href' => elgg_get_site_url() . $Pid ."/add",
	    'text' => elgg_echo('Crea Nuova'),
// 'class' => 'elgg-menu-hz',
	    'class' => 'elgg-button elgg-button-delete',
    ))."\n\r<br/><br/><br/>";