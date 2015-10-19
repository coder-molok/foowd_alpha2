<?php

// return;


/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @package Elgg
 * @subpackage Core
 *
 */

echo elgg_view('search/search_box', array('class' => 'elgg-search-header'));

elgg_push_context('owner_block');

// manu di ricerca del plugin search advance

// groups and other users get owner block
$owner = elgg_get_page_owner_entity();
if ($owner instanceof ElggGroup || $owner instanceof ElggUser) {

	// $header = elgg_view_entity($owner, array('full_view' => false));

	// $body = elgg_view_menu('owner_block', array('entity' => $owner));

	$body .= elgg_view('page/elements/owner_block/extend', $vars);

	echo elgg_view('page/components/module', array(
		'header' => $header,
		'body' => $body,
		'class' => 'elgg-owner-block',
	));
}


elgg_pop_context();

?>


<!-- wrap plugin advanced - search per le opzioni sulle entita' per le quali effettura la ricerca -->
<!-- ulteriori wrap di questo menu si trovano nello start.php di questo plugin -->
<script>
	$(function(){
		// predispongo gli input affinche' la ricerca avvenga solo sugli utenti, escludendo cos= commenti, gruppi, etc.
		var sType = $('input[name="serach_type"]');
		sType.removeAttr('disabled');
		var eType = $('input[name="entity_type"]');
		eType.removeAttr('disabled');
		eType.attr('val', 'user');
		var Jel = $('ul.search-advanced-type-selection');
		
		// personalizzo la scritta: 
		// cosi' facendo rimuovo il dorp-down menu dal quale sarebbe possibile selezionare l'entita' su cui effettuare la ricerca, come utenti, gruppi, commenti, etc.
		Jel.html('Trova amici');
		Jel.css({
			'background-color': 'rgb(200, 16, 99)',
			'padding':'2px 7px 0px 3px',
			'color' : 'white',
			'width': '73px',
			'font-size': '0.9em'
		})

		// rimuovo la scritta "tutto dal menu"
		// cliccandola effettuerebbe la ricerca su tutte le entita' come i gruppi
		$('li.elgg-menu-item-all').remove();
	});
</script>
