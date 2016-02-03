<?php

// NB: attenzione a non inizializzare la variabile $_SESSION['sticky_forms']['nome del form']
// 			altrimenti al reload non si realizzerebbe la condizione per il richiamo delle API in single.php

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\Action\FormAdd($vars);

if($vars['Id']==='' || $vars['guid']==='' ){

	echo '<div style="color:red;">Attenzione! Problema con la modifica </div>';
	return;
}


if(isset($vars['offerPrefers'])){
	$v = $vars['offerPrefers'];
	$txt = "";
	if(count($v['pending']) > 0){
		$txt = "
		<div class=\"foowd-advise-pending\">Questa offerta non e\' al momento modificabile perch&eacute; coinvolta in ordini pendenti.</div>
		";
	}
	elseif(count($v['newest']) > 0){
		$time = $vars['offerModifyExpiration']['time'];
		// \Fprint::r($time);
		// la prima volta non ho ancora creato una modifica, pertanto posso solo avvisare
		if($time == ''){
			$txt = "
			<div class=\"foowd-advise-newest\">Una volta modificata questa offerta avrai circa un'ora di tempo per fare altre modifiche.<br/>Dopo di che verr&agrave; inviata comunicazione delle modifiche agli utenti interessati.</div>
			";
		}else{	
			$txt = "
			<div class=\"foowd-advise-newest\">Una volta modificata questa offerta potrai fare altre modifiche fino alle ore $time.<br/>Dopo di che verr&agrave; inviata comunicazione delle modifiche agli utenti interessati.</div>
			";
		}
	}

	echo $txt;
}



// $fadd->createField('Name', 'Offerta', 'input/text');
$fadd->createField('Name', 'foowd:name:need', 'input/text');

// $fadd->createField('Description', 'Descrivi il tuo prodotto', 'input/longtext');
$fadd->createField('Description', 'foowd:description:need', 'input/longtext'); // foowd_textarea

// la prima volta non dovrebbe essere impostato niente, e visualizzo soltanto il form di caricamento
// $fadd->createField('file', 'Carica l\'immagine', 'input/file', array('id'=>'loadedFile', 'value'=>''));
$fadd->createField('file', 'foowd:file:need', 'input/file', array('id'=>'loadedFile', 'value'=>''));

// div image se esiste img
$dir = \Uoowd\Param::pathStore($vars['guid'],'offers').$vars['Id'].'/';
// echo $dir;
$style = 'style="display:none;"';

if(file_exists($dir)){
	foreach( new \DirectoryIterator($dir) as $single){
		// non faccio controlli particolari per ora
		if($single->isFile() && $single->getExtension() !== 'json'  ){
		 	$img = $single->getPathname();
		 	// break;
		 }elseif($single->getExtension() === 'json'){
		 	$oldCrop = json_decode( file_get_contents($single->getPathname()) );
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
<noscript>Javascript disattivato: <br/> Salva per visualizzare la nuova immagine.</noscript>
<?php

echo '<div id="image">'.$img.'</div></div></center>';

// $fadd->createField('Tag', 'Tags (selezionane almeno uno) *', 'input/checkbox', array('inputs' => $vars['Tag'], 'attributes' =>$vars['TagAttributes']) );
$fadd->createField('Tag', 'foowd:tag:need', 'input/checkbox', array('inputs' => $vars['Tag'], 'attributes' =>$vars['TagAttributes']) );
// $fadd->createField('Price', 'Importo', 'input/spinner', array("decimal"=>2, "integer"=>"8"));
$fadd->createField('Quota', 'foowd:quota:need', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Unit','foowd:unit:need', 'input/select', $vars['_Unit']);
$fadd->createField('UnitExtra','foowd:unit:extra', 'input/text', array('maxlength'=>"30"));
?>
<label for="quota-preview"><?php echo elgg_echo('foowd:quota:preview'); ?></label>
<div id="quota-preview"></div>
<?php
$fadd->createField('Price','foowd:price:need', 'input/text', array('maxlength'=>"11"));
// $fadd->createField('Minqt', 'Quantita\' minima', 'input/spinner', array("decimal"=>3, "integer"=>5));
$fadd->createField('Minqt', 'foowd:minqt:need', 'input/text', array('maxlength'=>"9"));
// $fadd->createField('Maxqt', 'Quantita\' massima', 'input/spinner', array("decimal"=>3, "integer"=>5));
$fadd->createField('Maxqt', 'foowd:maxqt', 'input/text', array('maxlength'=>"9"));

// il javascript e la struttura sono in FormAdd: hookCreateExpiration
$fadd->createField('Expiration', 'foowd:expiration', '', array('Expiration'=>$vars['Expiration']));

// variabile per il controllo su cambiamenti dell'immagine di default
echo elgg_view('input/hidden', array('name' => 'fileBasename', 'value' => basename($path)) ); 


?>

<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<div>

<div>
    <?php echo '* : campo obbligatorio.'; ?>

</div>
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
    <input type="hidden" name="crop[x1]" value="<?php echo $oldCrop->x1; ?>" />
    <input type="hidden" name="crop[y1]" value="<?php echo $oldCrop->y1; ?>" />
    <input type="hidden" name="crop[x2]" value="<?php echo $oldCrop->x2; ?>" />
    <input type="hidden" name="crop[y2]" value="<?php echo $oldCrop->y2; ?>" />    
</div>
<a href="image-tmp" id="url" style="display:none;">testo</a>



<!-- colleziono gli elementi come hook per javascript sui contenuti modificati -->
<ul id="foowd-edited" style="display:none;">
	<?php
		if(is_array($vars['edited'])) foreach($vars['edited'] as $v) echo "<li data-edited=\"$v\">" . trim($v) . "</li>";
	?>
</ul>




<script>
require(['jquery','elgg'], function($, elgg){
	/*
	if($('.foowd-advise-pending').length > 0){
		$('.foowd-page-single').css('background-color', 'orange')
		$('.foowd-page-single *').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();
			elgg.register_error('Offerta Bloccata');
		});
	}
	*/
	// azioni da svolgere relativamente ai campi modificati
	$('#foowd-edited li').each(function(){
		var field = $(this).attr('data-edited');
		$('label[for="' + field + '"]').parent().addClass('foowd-advise-modified');
	});
});
</script>

<?php elgg_load_js('jquery'); ?>
<link href="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css" rel="stylesheet">
<!-- <script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/scripts/jquery.min.js"></script> -->
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
<?php 
	elgg_require_js(\Uoowd\Param::pid().'/use_crop'); 
	elgg_require_js(\Uoowd\Param::pid().'/offer-form-check.amd'); 
	// echo \Uoowd\Param::pid().'/use_crop';
