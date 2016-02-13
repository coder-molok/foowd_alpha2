<?php

admin_gatekeeper();

ob_start();

/*

Iter: 

l'utente si iscrive e riceve la mail per confermare la sua iscrizione

alla conferma visualizziamo un messaggio di presa in valutazione e mando mail agli amministratori

gli amministratori poi lo modificano

*/


?>

<div>
	Cliccando sul link accanto a ogni produttore accederai alla sua pagina profilo, da cui potrai approvarlo.
</div>


<?php


$aprs = elgg_get_entities_from_metadata(
	// array('metadata_names'=>array('Genre'), 'metadata_values'=>array('standard'))
	// array('metadata_names'=>array('fake'), 'metadata_values'=>array('lol'))
	array( 'metadata_names'=>array('Genre'), 'metadata_values'=>array('evaluating') )
	);


$model = <<< __SINGLE
	<div class="single">
		<div class="user">Utente:  %s</div>
		<div class="link">Guid:    %s</div>
		<div class="link">Link:   <a href="%s" target="_blank">%s</a></div>
	</div>	
__SINGLE;

foreach($aprs as $s ){
	$guid = $s->guid;
	$url = \Uoowd\Param::userPath('settings', $guid);

	echo sprintf($model, $s->username, $guid, $url, $url);
}







$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-evaluatingUsers">'.$body.'</div>';

echo elgg_view_page('Approva',$body);