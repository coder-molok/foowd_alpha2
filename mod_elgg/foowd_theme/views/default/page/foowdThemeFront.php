<?php
/**
 * Prototipo pagina Thema di elgg-foowd:
 *
 * La pagina carica tutto quello che e' standard,
 * in questo modo i temi di front possono essere sviluppati in maniera totalmente indipendente da elgg
 */


// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
	if (get_input('handler') != 'admin') {
		elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
	}
	_elgg_admin_add_plugin_settings_menu();
	elgg_unregister_css('elgg');
	echo elgg_view('page/admin', $vars);
	return true;
}

// render content before head so that JavaScript and CSS can be loaded. See #4032

$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
// 
// $header = '';//elgg_view('page/elements/header', $vars);
$content = elgg_view('page/elements/body', $vars);

$body = <<<__BODY
<div class="elgg-page elgg-page-default">
	<div class="elgg-page-messages">
		$messages
	</div>
__BODY;

$body .= <<<__BODY

			$content

	<div class="elgg-page-footer-frontend">
		<div class="elgg-inner">
			$footer
		</div>
	</div>
</div>
__BODY;

// prima qui vi era head
$head = elgg_view('page/elements/foowdHeadFront', $vars['head']);



/**
 * 
 * COMPLETO IL BODY e la generazione della pagina HTML
 * 
 */

$body .= elgg_view('page/elements/foot');
$params = array(
	'head' => $head,
	'body' => $body,
);

if (isset($vars['body_attrs'])) {
	$params['body_attrs'] = $vars['body_attrs'];
}



echo elgg_view("page/elements/html", $params);
