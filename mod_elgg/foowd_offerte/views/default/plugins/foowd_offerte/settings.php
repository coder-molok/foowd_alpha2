<?php 

   $value = elgg_get_plugin_setting('api', \Foowd\Param::pid() );
   $value = ($value) ? $value : \Foowd\Param::apiDom();

?>

<p>
	<?php echo 'Server API:';  ?>
   <input type="text" name="params[api]" size="50" value="<?php echo $value; ?>" />

</p>


<?php 

   $value = elgg_get_plugin_setting('dbg', \Foowd\Param::pid() );
   
   // se non e' impostato, lo imposto
   $value = ($value) ? $value : \Foowd\Param::$par['dbg'];
   
	if($value){
		$str = 'spunta per disabilitare il debug';
		$v = 0;
	}else{
		$str = 'spunta per abilitare il debug';
		$v = 1;
	}

	//var_dump($vars['entity']->dbg);

?>

<p>
	<?php echo 'Debug Foowd:<br/>';  ?>
	<!-- 0 vuol dire false -->
	<!-- quando e' checked salva il valore impostato, altrimenti non fa nulla -->

   <input type="checkbox"  name="params[dbg]" value="<?php echo $v;?>" /> <?php echo $str; ?>


</p>

<?php

// echo elgg_view('input/checkboxes', array(
//             'options' => array('spunta per attivare il debug' => 'dbg' ) ,
//             'value' => 0,//$vars['entity']->dbg,// 1 spuntato, 2 non
//             'name' => 'params[dbg]',
//             //'align' => 'vertical',
//         ));