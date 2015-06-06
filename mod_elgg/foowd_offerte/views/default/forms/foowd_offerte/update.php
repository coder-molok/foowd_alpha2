<?php
// /views/default/input/

// NB: attenzione a non inizializzare la variabile $_SESSION['sticky_forms']['nome del form']
// 			altrimenti al reload non si realizzerebbe la condizione per il richiamo delle API in single.php

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\Action\FormAdd($vars);

if($vars['Id']==='' || $vars['guid']==='' ){

	echo '<div style="color:red;">Problema nella modifica del post</div>';
	return;
}


$fadd->createField('Name', 'Offerta', 'input/text');
$fadd->createField('Description', 'Descrivi il tuo prodotto', 'input/longtext');

// la prima volta non dovrebbe essere impostato niente, e visualizzo soltanto il form di caricamento
$fadd->createField('file', 'Carica l\'immagine', 'input/file', array('id'=>'loadedFile', 'value'=>''));

// div image se esiste img
$dir = \Uoowd\Param::imgStore().'User-'.$vars['guid'].'/'.$vars['Id'].'/';
// echo $dir;
$style = 'style="display:none;"';
if(file_exists($dir)){
	foreach( new \DirectoryIterator($dir) as $single){
		// non faccio controlli particolari per ora
		if($single->isFile() && $single->getExtension() !== 'json'  ){
		 	$img = $single->getPathname();
		 	break;
		 }
	}
	if($img) {

		$img = str_replace('\\','/', $img);
		$path = $img;
		$type = pathinfo($img, PATHINFO_EXTENSION);
		$img = file_get_contents($img);
		$img = 'data:image/' . $type . ';base64,' . base64_encode($img);
		$img = '<img src="'.$img.'" width="400px" />';
		$style = '';
	}
}
echo '<center><div id="image-container" '.$style.' >';
?>
<script> document.write('Seleziona l\'area da ritagliare.'); </script>
<noscript>Javascript disattivato: <br/> visualizzerai la nuova immagine dopo il salvataggio.</noscript>
<?php

echo '<div id="image">'.$img.'</div></div></center>';

$fadd->createField('Price', 'Importo', 'input/spinner', array("decimal"=>2, "integer"=>"8"));

$fadd->createField('Tag', 'Tags (selezionane almeno uno) *', 'input/checkbox', array('inputs' => $vars['Tag'], 'attributes' =>$vars['TagAttributes']) );

$fadd->createField('Minqt', 'Quantita\' minima', 'input/spinner', array("decimal"=>3, "integer"=>5));
$fadd->createField('Maxqt', 'Quantita\' massima', 'input/spinner', array("decimal"=>3, "integer"=>5));

// variabile per il controllo su cambiamenti dell'immagine di default
echo elgg_view('input/hidden', array('name' => 'fileBasename', 'value' => basename($path)) ); 

?>

<div>
    <input type="hidden" name="Id" value="<?php echo $vars['Id']; ?>" />
</div>

<div class="elgg-foot">
    <?php 
        // la guid mi serve per salvare il file temporaneo
        echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
        if(isset($vars['sticky'])) echo elgg_view('input/hidden', array('name' => 'sticky', 'value' => $vars['sticky'])); 
    ?>
</div> 
<div id="crop">
    <input type="hidden" name="crop[x1]" value="" />
    <input type="hidden" name="crop[y1]" value="" />
    <input type="hidden" name="crop[x2]" value="" />
    <input type="hidden" name="crop[y2]" value="" />    
</div>
<a href="image-tmp" id="url" style="display:none;">testo</a>

<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<div>
    <?php echo '* : campo obbligatorio.'; ?>
</div>


<?php elgg_load_js('jquery'); ?>
<link href="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/css/imgareaselect-default.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/scripts/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
<?php elgg_require_js(\Uoowd\Param::pid().'/use_crop'); 
// echo \Uoowd\Param::pid().'/use_crop';
