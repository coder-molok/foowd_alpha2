<?php 

   $value = elgg_get_plugin_setting('api', \Foowd\Param::pid() );
   $value = ($value) ? $value : \Foowd\Param::apiDom();

?>

<p>
	<?php echo 'Server API:';  ?>
   <input type="text" name="params[api]" size="50" value="<?php echo $value; ?>" />

</p>