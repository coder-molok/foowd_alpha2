<?php
/**
 * The HTML head
 * 
 * JavaScript load sequence (set in views library and this view)
 * ------------------------
 * 1. Elgg's initialization which is inline because it can change on every page load.
 * 2. RequireJS config. Must be loaded before RequireJS is loaded.
 * 3. RequireJS
 * 4. jQuery
 * 5. jQuery migrate
 * 6. jQueryUI
 * 7. elgg.js
 *
 * @uses $vars['title'] The page title
 * @uses $vars['metas'] Array of meta elements
 * @uses $vars['links'] Array of links
 */

$metas = elgg_extract('metas', $vars, array());
$links = elgg_extract('links', $vars, array());

echo elgg_format_element('title', array(), $vars['title'], array('encode_text' => true));
foreach ($metas as $attributes) {
	echo elgg_format_element('meta', $attributes);
}
foreach ($links as $attributes) {
	echo elgg_format_element('link', $attributes);
}

$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();
$elgg_init = elgg_view('js/initialize_elgg');

$html5shiv_url = elgg_normalize_url('vendors/html5shiv.js');
$ie_url = elgg_get_simplecache_url('css', 'ie');

?>

	<!--[if lt IE 9]>
		<script src="<?php echo $html5shiv_url; ?>"></script>
	<![endif]-->

<?php

foreach ($css as $url) {
	echo elgg_format_element('link', array('rel' => 'stylesheet', 'href' => $url));
}

?>
	<!--[if gt IE 8]>
		<link rel="stylesheet" href="<?php echo $ie_url; ?>" />
	<![endif]-->

	<script><?php echo $elgg_init; ?></script>
<?php
foreach ($js as $url) {
	echo elgg_format_element('script', array('src' => $url));
}

echo elgg_view_deprecated('page/elements/shortcut_icon', array(), "Use the 'head', 'page' plugin hook.", 1.9);

echo elgg_view_deprecated('metatags', array(), "Use the 'head', 'page' plugin hook.", 1.8);
?>
<!-- <div class="foowd-header">foowd_</div> -->

<!-- <div class="foowd-navbar">
</div> -->

<!-- pagina head.php -->

<script type="text/javascript" src="<?php echo elgg_get_site_url();?>/mod/foowd_theme/vendor/modernizr/modernizr.js"></script>

<script type="text/javascript">
require([ 
  	'NavbarController',
  ],function(){
  	
    require('NavbarController').loadNavbar();

});
</script>
<style>
.foowd-navbar{
	position: relative;
	z-index:0;
}
</style>

<?php
// link back to main site.
// echo elgg_view('page/elements/header_logo', $vars);

// drop-down login
// echo elgg_view('core/account/login_dropdown');

// echo '<div class="elgg-heading-site"><a href="'.elgg_get_site_url().'" style="color:white;position:relative; top: -10px;">Home</a></div>';

 
