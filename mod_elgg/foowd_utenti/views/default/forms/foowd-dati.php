<?php

// var_dump($vars);

$fadd = new \Foowd\Action\UserSave($vars);


$fadd->createField('Name','Nome', 'input/text');
$fadd->createField('Location','Luogo', 'input/text');
$fadd->createField('Email','Email', 'input/text');


// opzioni disponibili
$options_values = array("Utente Regolare","Offerente");

// ed i rispettivi valori
//$options = array("standard","offerente");

// antepongo Genre- come aggancio al metodo isEnum della fowd/action/register che utilizzo per il controllo
$options = array(
	"offerente" => "Offerente",
	"standard"	=> "Utente Regolare"
	);

// imposto i valori di default
$defaults = array('name' => 'Genre', 'options_values' => $options, $value => 'seleziona...');

// cambio l'eventuale valore di default
if (isset($vars['entity'])) { 
	$defaults['value'] = $vars['entity']->Genre;
	unset($vars['entity']); 
}
//echo elgg_view('input/dropdown', $vars);

$vars = array_merge($defaults, $vars);

// istanzio per la creazione
// $fadd = new \Foowd\Action\Register();
$fadd->createField('Genre','Quale utente vuoi essere?', 'input/dropdown', $vars);


?>
<div id="offer-hook">
	<?php
		// $fadd = new \Foowd\Action\FormAdd($vars);
		

		$fadd->createField('Description', 'foowd:user:description:need', 'input/longtext');

		
		// $fadd->createField('file1', 'foowd:file:need', 'input/file', array('value'=>''));
		// echo '<center><div id="image-container" style="display:none;">Seleziona l\'area da ritagliare.<div id="image"></div></div></center>'; 
	?>
	<!-- <center>
		<div id='file1-container'></div>
	</center> -->
	<!-- deve essere nel formato: crop_{nome dell'input file a cui si riferisce}[x1], etc -->
	<!-- <div class="crop">
	    <input type="hidden" name="crop_file1[x1]" value="" />
	    <input type="hidden" name="crop_file1[y1]" value="" />
	    <input type="hidden" name="crop_file1[x2]" value="" />
	    <input type="hidden" name="crop_file1[y2]" value="" />    
	</div> -->

	<a href="<?php echo elgg_echo('foowd:image-tmp')?>" id="url" style="display:none" >testo</a>

	<?php
	$fadd->createField('Piva','foowd:user:piva:need', 'input/text', array('maxlength'=>"11"));
	$fadd->createField('Address','foowd:user:address:need', 'input/text', array('maxlength'=>"150"));
	$fadd->createField('Company','foowd:user:company:need', 'input/text', array('maxlength'=>"100"));
	
	$fadd->createField('Site','foowd:user:site:optional', 'input/text', array('maxlength'=>"255"));
	$fadd->createField('Phone','foowd:user:phone:need', 'input/text', array('maxlength'=>"15"));
	?>

<div class="legend">
*  : campo opzionale.<br/>
** : campo obbligatorio per la registrazione come <strong>offerente</strong>.
</div>
</div><!-- end #offer-hook -->

<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<?php

elgg_require_js('foowd_utenti/user-dati');
elgg_require_js('foowdFormCheck');