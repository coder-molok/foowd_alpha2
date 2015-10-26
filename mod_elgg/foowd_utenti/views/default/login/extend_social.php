<?php

// echo 'estendo il login!!!';
// var_dump( elgg_get_context() );
// sidebar main
// /login  login

$attr['height'] = '40px';
$attr['width'] = $attr['height'];
$attr['line-height'] = $attr['height'];
$attr['font-size'] = '18px';

$style = ' style=" ';
foreach($attr as $at => $set){
	$style .= $at.':'.$set.'; ';
}
$style .='"';

?>

<h3 class="elgg-heading-main elgg-head">Oppure accedi tramite un social:</h2>

<ul class="soc foowd-alert-disabled">
    <li><a class="soc-facebook" <?php echo $style; ?> href="<?php echo \Uoowd\Param::page()->auth; ?>?provider=Facebook"></a></li>
    <li><a class="soc-google soc-icon-last" <?php echo $style; ?> href="<?php echo \Uoowd\Param::page()->auth; ?>?provider=Google"></a></li>
</ul>


