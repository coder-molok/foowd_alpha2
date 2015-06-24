<?php

// definito in \Foowd\SocialLogin, metodo registerUser
$idAuth = get_input('idAuth');

if(isset($idAuth)){
	// var_dump($idAuth);
	echo elgg_view('input/hidden', array('name' => 'idAuth', 'value' => $idAuth)); 
}
// var_dump($_SESSION);

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\Action\Register($vars);

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
$fadd = new \Foowd\Action\Register();
$fadd->createField('Genre','Quale utente vuoi essere?', 'input/dropdown', $vars);

?>
<div id="offer-hook">
	<?php
		// $fadd = new \Foowd\Action\FormAdd($vars);

		$fadd->createField('Description', 'foowd:user:description', 'input/longtext');

		// la prima volta non dovrebbe essere impostato niente, e visualizzo soltanto il form di caricamento
		$fadd->createField('file', 'foowd:file:need', 'input/file', array('id'=>'loadedFile', 'value'=>''));
		echo '<center><div id="image-container" style="display:none;">Seleziona l\'area da ritagliare.<div id="image"></div></div></center>';
	?>
	<div id="crop">
	    <input type="hidden" name="crop[x1]" value="" />
	    <input type="hidden" name="crop[y1]" value="" />
	    <input type="hidden" name="crop[x2]" value="" />
	    <input type="hidden" name="crop[y2]" value="" />    
	</div>
	<a href="<?php echo elgg_echo('foowd:image-tmp')?>" id="url" style="display:none" >testo</a>
</div>
<?php

elgg_require_js('foowd_utenti/user-register');
