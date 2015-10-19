<?php
  // NB1:  quando il plugin e' disattivato, questa pagina non e' visualizzabile
  // 
  // NB2:  tutti i parametri di questo plugin salvati, rimangono conservati in elgg 
  //      e disponibili agli altri plugin, anche quando il plugin stesso e' disattivato.
  //      Pertanto ha senso mantenere un parametro di controllo sul plugin setto come
  //      avviene per 'forceActiveAll' creato in fondo
?>

<!--------------- Test Iniziale ------------------>
<p>
  <?php echo '<h1>Test Iniziale</h1><br/>';  ?>
  <p>Cliccando puoi verificare che siano abilitati i principali servizi necessari per l'utilizzo dei plugin foowd.</p>
   <a class="elgg-button elgg-button-submit" href="<?php echo elgg_get_site_url().\Uoowd\Param::pid(); ?>/checkInit">Test</a>
</p>
<br/>



<!--------------- API ------------------>

<?php 

   $value = elgg_get_plugin_setting('api', \Uoowd\Param::pid() );
   $value = ($value) ? $value : \Uoowd\Param::apiDom();
   //var_dump(\Uoowd\Param::pid());
?>

<p>
	<?php echo '<h1>Server API:</h1><br/>';  ?>
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
			$value = '""';
		}
	} 

  // per precauzione controllo che sia un formato json, altrimenti lo imposto come stringa vuota
  // questo serve per la parte javascript

  json_decode($value);
  if(json_last_error() !== JSON_ERROR_NONE){
    $value = '""';
    echo '<div style="background-color:red; margin:10px;">Tags non presenti o salvati in maniera errata.</div>';
  }

	// registro un hook a questo submit

	echo '<h1>TAGS:</h1><br/>';  
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




<!--------------- SOCIALS ------------------>
<h1>Socials</h1>
<br/>
<?php 
  $socials = array('Google-Id', 'Google-Secret', 'Facebook-Id','Facebook-Secret');

  foreach($socials as $s){
    $value = elgg_get_plugin_setting($s, \Uoowd\Param::pid() );

    echo '<p>'.
          '<label>'.$s.'</label><br/>'.
          '<input class="socials" type="text" name="params['.$s.']" size="80" value="'.$value.'" />'.
          '</p>';       

  }
   
?>



<!--------------- PHPMAILER ------------------>
<style>
.mailer p{
  width: 50%;
  float: left;
}

.mailer p input{
  width: 90%;
}
</style>
<br/>
<h1>PhpMailer</h1>
<br/>
<?php
$p = 'phpmailer-enable';
$value = elgg_get_plugin_setting($p, \Uoowd\Param::pid() );
// se non e' impostato, lo imposto
$value = ($value) ? $value : \Uoowd\Param::$par['dbg']; 

$checked = ($value) ? true : false ;

echo elgg_view("input/checkbox", array(
    'label' => 'spunta per attivare l\'invio tramite phpmailer',
    'name'  => "params[$p]",
    'checked' => $checked
  ));
?>
<div>
  NB: l'invio tramite smpt sostituira' l'invio tramite la funzione <b><i>mail()</i></b> di PHP.
</div>
<br/>
<div class="mailer">
<?php 
  $mailcfgs = array('Host', 'Username','Password', 'From', 'FromName', 'SMTPSecure', 'Port', 'SMTPAuth');

  foreach($mailcfgs as $s){
    $p = 'phpmailer-' . $s;
    $value = elgg_get_plugin_setting($p, \Uoowd\Param::pid() );

    echo '<p>'.
          '<label>'.$s.'</label><br/>'.
          '<input class="phpmailer" type="text" name="params['.$p.']" size="80" value="'.$value.'" />'.
          '</p>';       

  }
   
?>
</div>
<br/>


<!--------------- DEVELOPERS ------------------>
<br>
<h1>Per Sviluppatori</h1>
<br>

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
    echo '<h3>Debug LEVEL: </h3><br/>'; 
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




<!--------------- DEBUG FOOWD ------------------>
<p>
  <!-- quando e' checked salva il valore impostato, altrimenti non fa nulla -->
<?php 

  echo '<h3>Debug Foowd:</h3>(utilizzato per visualizzare un messaggio register_error)<br/><br/>'; 

  // NB: se spuntato, quando salva gli da valore 'on' (true)
  //      altrimenti '0' (false)

  $value = elgg_get_plugin_setting('dbg', \Uoowd\Param::pid() );
  // se non e' impostato, lo imposto
  $value = ($value) ? $value : \Uoowd\Param::$par['dbg']; 

  $checked = ($value) ? true : false ;

  echo elgg_view("input/checkbox", array(
      'label' => 'spunta per attivare il debug',
      'name'  => "params[dbg]",
      'checked' => $checked
    ));



  echo '<br/>';
  $value = elgg_get_plugin_setting('forceActivateAll', \Uoowd\Param::pid() );
  $checked = ($value) ? true : false ;
  echo elgg_view("input/checkbox", array(
      'label' => 'spunta per attivare forzare la riattivazione automatica dei plugins foowd',
      'name'  => "params[forceActivateAll]",
      'checked' => $checked
    ));


?>
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