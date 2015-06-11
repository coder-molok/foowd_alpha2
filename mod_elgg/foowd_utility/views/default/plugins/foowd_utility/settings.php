
<!--------------- API ------------------>

<?php 

   $value = elgg_get_plugin_setting('api', \Uoowd\Param::pid() );
   $value = ($value) ? $value : \Uoowd\Param::apiDom();
   //var_dump(\Uoowd\Param::pid());
?>

<p>
	<?php echo '<label>Server API:</label><br/>';  ?>
   <input type="text" name="params[api]" size="50" value="<?php echo $value; ?>" />

</p>


<!--------------- TAGS ------------------>
<div id="tags-hook">

	<?php 
	

	elgg_load_js("jquery");
  // elgg_unset_plugin_setting('tags', \Uoowd\Param::pid() );
	$value = elgg_get_plugin_setting('tags', \Uoowd\Param::pid() );
	$json = \Uoowd\Param::tags();
  
  tagsBackup(dirname($json));

  // se il tags non e' salvato come settings o se e' vuoto
  if(!$value || trim($value)===''){
		// echo $json;
		// se non esiste il file json in cui salvarlo
		if(file_exists($json)){
			// echo 'carico';
			$value =  file_get_contents($json);
		}else{
			$value = '';
		}
	} 



	// registro un hook a questo submit

	echo '<label>TAGS:</label><br/>';  
  ?>
  <div style="font-style: italic; font-size:11px;">
    Cliccando sui nomi dei tags aggiunti potrai cancellarli.<br/>
    NB: singole parole con lettere MINUSCOLE.
  </div>
  <!-- mi serve js perche' la validita' dei tags la testo prima del submit -->
  <noscript><div style="color:red;">Mi dispiace, ma per inserire i tags devi avere abilitato javascript.</div></noscript>
   <input type='hidden' id="tags" name="params[tags]" value=<?php echo $value;?> />
</div>


<?php 

elgg_require_js('foowd_utility/plugin-settings');
// il comando sopra stampa
// <script>require(['foowd_utility/plugin-settings']) </script>

$css_url = 'mod/foowd_utility/views/default/js/foowd_utility/plugin-settings.css';
elgg_register_css('plugin-settings', $css_url);
elgg_load_css('plugin-settings');

?>

<!--------------- LOG LEVEL ------------------>

<?php 



   $value = elgg_get_plugin_setting('LEVEL', \Uoowd\Param::pid() );
   
   // se non e' impostato, lo imposto
   $value = ($value) ? $value : 'NULL';
   
  if($value){
    $str = 'spunta per disabilitare il debug';
    $v = 0;
  }else{
    $str = 'spunta per abilitare il debug';
    $v = 1;
  }

  // Levels by RFC 5424

  $levels = array( 'DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY');
  // DEBUG (100): Detailed debug information.
  // INFO (200): Interesting events. Examples: User logs in, SQL logs.
  // NOTICE (250): Normal but significant events.
  // WARNING (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
  // ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
  // CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.
  // ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
  // EMERGENCY (600): Emergency: system is unusable.

?>

<p>
  <?php 
    echo '<label>Debug LEVEL: </label>(per gli sviluppatori)<br/>'; 
    echo 'Valore Attuale: '. $value ; 
    echo elgg_view('input/select',array(
      'name' => 'params[LEVEL]',
      //'value' => 3,
      'options' => $levels, 
      'value' => array($value), // valore predefinito: quello con select
      'style' => 'margin-left: 170px;'
      )

    );
  ?>
  <!-- 0 vuol dire false -->
  <!-- quando e' checked salva il valore impostato, altrimenti non fa nulla -->
</p>




<!--------------- LOGGER ------------------>

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


?>

<p>
	<?php echo 'Debug Foowd:<br/> (utilizzato per visualizzare un messaggio register_error)<br/>';  ?>
	<!-- 0 vuol dire false -->
	<!-- quando e' checked salva il valore impostato, altrimenti non fa nulla -->

   <input type="checkbox"  name="params[dbg]" value="<?php echo $v;?>" /> <?php echo $str; ?>

</p>

<?php

function tagsBackup($dir){

  $saveDir = $dir.'/backup/';

  $created = true;
  if (!file_exists($saveDir)) {
      $created = mkdir($saveDir, 0777, true);
  }
  if(!$created){
    var_dump('Attenzione, la directory di backup non e\' stata creata.');
    return;
  }

  // ad ogni salvataggio dei settings viene generato un file tags.json,
  // che pertanto e' il piu' recente e ne salvo una copia di backup
  $src = $dir.'/tags.json';
  $time = filemtime($src);
  $date = date('Y-m-d', $time);
  $dest = $saveDir.$date.'_tags.json';
  copy($src, $dest);

  // tengo memorizzati solo gli ultimi 10 backup
  foreach( new \DirectoryIterator($saveDir) as $f ){
    if($f->isFile() && $f->getExtension() == 'json'){

      // $ar['date']=date('Y-m-d', $f->getATime());
      // $ar['bname']=$f->getFilename();
      // $ar['fname']= pathinfo($f->getFilename(), PATHINFO_FILENAME);
      $ar['path']=$f->getPathname();
      $files[$f->getMTime()] = $f->getPathname();
    
    }
  }

  // riordino dalla piu recente alla piu vecchia
  ksort($files);
  $files = array_reverse($files);

  // tengo solo le ultime 7
  
}