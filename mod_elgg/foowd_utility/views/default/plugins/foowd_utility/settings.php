<?php 

   $value = elgg_get_plugin_setting('api', \Uoowd\Param::pid() );
   $value = ($value) ? $value : \Uoowd\Param::apiDom();
   //var_dump(\Uoowd\Param::pid());
?>

<p>
	<?php echo '<label>Server API:</label><br/>';  ?>
   <input type="text" name="params[api]" size="50" value="<?php echo $value; ?>" />

</p>



<p>

	<?php 
	

	elgg_load_js("jquery");

	$value = elgg_get_plugin_setting('tags', \Uoowd\Param::pid() );
	// var_dump($value);

	// se il tags non e' salvato come settings o se e' vuoto
	if(!$value || trim($value)===''){
		$json = \Uoowd\Param::tags();
		// echo $json;
		// se non esiste il file json in cui salvarlo
		if(file_exists($json)){
			// echo 'carico';
			$value = (array) json_decode(file_get_contents($json));
			$value = $value['tags'];
		}else{
			$value = '';
		}
	} 

	// numero di riche
	$row = count(explode("\n", $value));

	// registro un hook a questo submit

	echo '<label>TAGS:</label><br/>';  
	?>
	<!-- mi serve js perche' la validita' dei tags la testo prima del submit -->
	<noscript><div style="color:red;">Mi dispiace, ma per inserire i tags devi avere abilitato javascript.</div></noscript>
   <textarea id="tags" name="params[tags]" rows="<?php echo $row; ?>" cols="50"><?php echo $value; ?></textarea>
   <div style="font-style: italic; font-size:11px;">Puoi inserire singole parole separate da virgola e andare a capo.</div>
   <?php //echo elgg_view('input/longtext', array('name'=>'params[tags]') );?>

</p>
<script>
$(function(){
	// $('#tags').css('overflow', 'hidden');
  $('#tags').on('keyup', function(){
    var offset = this.offsetHeight - this.clientHeight;
    $(this).css('height', 'auto').css('height', this.scrollHeight + offset);
  });
  // faccio qui un check dei tags
  $('form').on('submit', function(evt){
  	var tags = $('#tags').val();

  	var check = true;

  	// elimino le linee vuote
  	tags = tags.replace(/^\s*[\r\n]/gm,'');
  	// elimino la virgola finale e la riga vuota
  	// tags = tags.replace(/,\n$/g,'').replace(/^$/, '');
  	$('#tags').val(tags);
  	
  	tags = tags.split(/\r?\n/);
  	for(var i in tags){

  		line = tags[i];
  		console.log(line);

  		if(!line.match(/, +$/)) tags[i] += ',';

  		if(i == tags.length-1) tags[i] = tags[i].replace(/,$/g, "");
  		console.log(JSON.stringify(tags[i]));

  		if(line.match(/[\u00E0-\u00FC]/gi)){
  			alert('lettere accentate vietate');
  			check = false;
  		}

  		if(line.match(/\w+ +\w+/g)){
  			alert('tra due parole DEVI inserire la virgola');
  			check = false;
  		}
  		// elimino le virgole consecutive e gli spazi finali tra una virgola e la fine linea
  		tags[i] = tags[i].replace(/,+ +,+/g, '').replace(/ +$/g,'');

  		
  	}

  	// salvo i cambiamenti
  	$('#tags').val(tags.join(" \n"));
  	// check = false;
  	if(!check) evt.preventDefault();


  })
});
</script>





<?php 

   $value = elgg_get_plugin_setting('dbg', \Uoowd\Param::pid() );
   
   // se non e' impostato, lo imposto
   $value = ($value) ? $value : \Uoowd\Param::$par['dbg'];
   
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