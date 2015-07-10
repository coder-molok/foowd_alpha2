<?php

//NB: la registrazione da front-hand viene triggerata da user.php


// definito in \Foowd\SocialLogin, metodo registerUser
$idAuth = get_input('idAuth');

if(isset($idAuth)){
	// var_dump($idAuth);
	echo elgg_view('input/hidden', array('name' => 'idAuth', 'value' => $idAuth)); 
}
// var_dump($_SESSION);

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
// var_dump($vars);

$fadd = new \Foowd\Action\Register($vars);

$fadd->createField('Site','foowd:user:site:optional', 'input/text', array('maxlength'=>"255"));
$fadd->createField('Phone','foowd:user:phone:need', 'input/text', array('maxlength'=>"11"));

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

		// la prima volta non dovrebbe essere impostato niente, e visualizzo soltanto il form di caricamento
		// $fadd->createField('file-2', 'foowd:file:need', 'input/file', array('value'=>''));
		// $fadd->createField('file-3', 'foowd:file:need', 'input/file', array('value'=>''));
		// $fadd->createField('file-4', 'foowd:file:need', 'input/file', array('value'=>''));
		// $fadd->createField('file-5', 'foowd:file:need', 'input/file', array('value'=>''));
		$fadd->createField('file1', 'foowd:file:need', 'input/file', array('value'=>''));
		// echo '<center><div id="image-container" style="display:none;">Seleziona l\'area da ritagliare.<div id="image"></div></div></center>'; 
	?>
	<center>
		<div id='file1-container'></div>
	</center>
	<!-- deve essere nel formato: crop_{nome dell'input file a cui si riferisce}[x1], etc -->
	<div class="crop">
	    <input type="hidden" name="crop_file1[x1]" value="" />
	    <input type="hidden" name="crop_file1[y1]" value="" />
	    <input type="hidden" name="crop_file1[x2]" value="" />
	    <input type="hidden" name="crop_file1[y2]" value="" />    
	</div>

	<?php	$fadd->createField('file2', 'foowd:file:need', 'input/file', array('value'=>'')); ?>
	<center>
		<div id='file2-container'></div>
	</center>
	<!-- deve essere nel formato: crop_{nome dell'input file a cui si riferisce}[x1], etc -->
	<div class="crop">
	    <input type="hidden" name="crop_file2[x1]" value="" />
	    <input type="hidden" name="crop_file2[y1]" value="" />
	    <input type="hidden" name="crop_file2[x2]" value="" />
	    <input type="hidden" name="crop_file2[y2]" value="" />    
	</div>
	<a href="<?php echo elgg_echo('foowd:image-tmp')?>" id="url" style="display:none" >testo</a>

	<?php
	$fadd->createField('Piva','foowd:user:piva:need', 'input/text', array('maxlength'=>"11"));
	$fadd->createField('Address','foowd:user:address:need', 'input/text', array('maxlength'=>"150"));
	$fadd->createField('Company','foowd:user:company:need', 'input/text', array('maxlength'=>"100"));
	?>

</div><!-- end #offer-hook -->
<div class="legend">
*  : campo opzionale.<br/>
** : campo obbligatorio per la registrazione come <strong>offerente</strong>.
</div>
<?php

echo '';

elgg_require_js('foowd_utenti/user-register');
elgg_require_js('foowdFormCheck');
