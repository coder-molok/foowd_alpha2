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
