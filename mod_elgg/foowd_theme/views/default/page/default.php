<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body_attrs']  Attributes of the <body> tag
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
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

$header = '';//elgg_view('page/elements/header', $vars);
$content = elgg_view('page/elements/body', $vars);
$footer = elgg_view('page/elements/foowdFooterBack', $vars);

$guideUrl = \Uoowd\Param::page()->guide;

$body = <<<__BODY
<div class="elgg-page elgg-page-default">
	<div class="elgg-page-messages">
		$messages
	</div>
__BODY;

if (elgg_is_logged_in()) {
	$topbar = elgg_view('page/elements/topbar', $vars);

	$body .= <<<__BODY
	<div class="elgg-page-topbar">
		<div class="elgg-inner">
			$topbar
		</div>
	</div>
__BODY;
}	
$body .= <<<__BODY
	<div class="foowd-navbar"></div>
	<div class="elgg-page-header">
		<!-- <div class="elgg-inner"> -->
			$header
		<!-- </div> -->
	</div>
	<noscript>
	<div class="foowd-body-main-noscript">
		Questo sito usa javascript, ma attualmente non e' abilitato nel tuo brower.<br/>
		Per poter visualizzare a pieno il sito e godere delle sue funzionalit&aacute; ti suggeriamo di abilitare javascript o di accedere mediante un'altro browser.<br/><br/>
		Cordialmente,<br/> 
		lo Staff.
	</div>
	</noscript>
	<div class="elgg-page-body elgg-main foowd-theme-fadein">
		<div class="elgg-inner">
			$content
		</div>
	</div>
	<div class="elgg-page-footer foowd-theme-fadein">
		<div class="elgg-inner">
			$footer
		</div>
	</div>
</div>
__BODY;

// prima qui vi era head
$head = elgg_view('page/elements/foowdHeadBack', $vars['head']);



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
