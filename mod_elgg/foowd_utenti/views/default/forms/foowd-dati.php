<?php

// var_dump($vars);

$fadd = new \Foowd\Action\UserSave($vars);

// name non ha controlli
// $fadd->createField('Name','Nome', 'input/text');

// $fadd->createField('Location','Luogo', 'input/text');

// devo fare un chek
// $fadd->createField('Email','Email', 'input/text');





// fine dati di base 


/*
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
*/

if($vars['Genre']==='offerente'){
	$fadd->createField('Genre','', 'input/hidden', $vars);
}

?>
<div id="offer-hook">
	<?php
		// $fadd = new \Foowd\Action\FormAdd($vars);
		

	$fadd->createField('Description', 'foowd:user:description', 'input/longtext');
	$fadd->createField('Piva','foowd:user:piva', 'input/text', array('maxlength'=>"11"));
	$fadd->createField('Address','foowd:user:address', 'input/text', array('maxlength'=>"150"));
	$fadd->createField('Company','foowd:user:company', 'input/text', array('maxlength'=>"100"));
	
	$fadd->createField('Site','foowd:user:site:optional', 'input/text', array('maxlength'=>"255"));
	$fadd->createField('Phone','foowd:user:phone', 'input/text', array('maxlength'=>"15"));
	?>

<div class="legend">
*  : campo opzionale.<br/>
</div>
</div><!-- end #offer-hook -->

<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<?php

elgg_require_js('foowd_utenti/user-dati');
elgg_require_js('foowdFormCheck');