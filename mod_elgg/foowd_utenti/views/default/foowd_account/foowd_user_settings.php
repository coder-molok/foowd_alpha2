<?php

/**
 * Richiamato in  forms/account/settings.php di foowd_utenti
 */


// \Fprint::r($vars);

$fadd = new \Foowd\Action\UserSave($vars);
if(!$vars['isAdmin'] && $vars['Genre'] == 'standard') goto __skipDATA;
?>
<div id="offer-hook">
	<?php

		

	// $fadd->createField('Description', 'foowd:user:description', 'input/longtext');
	$fadd->createField('Owner','foowd:user:owner', 'input/text', array('maxlength'=>"100"));
	$fadd->createField('Piva','foowd:user:piva', 'input/text', array('maxlength'=>"11", 'disabled'=>!$vars['isAdmin']));
	$fadd->createField('Address','foowd:user:address', 'input/text', array('maxlength'=>"150"));
	$fadd->createField('Company','foowd:user:company', 'input/text', array('maxlength'=>"100"));
	
	$fadd->createField('Site','foowd:user:site:optional', 'input/text', array('maxlength'=>"255",));
	$fadd->createField('Phone','foowd:user:phone', 'input/text', array('maxlength'=>"15"));
	?>

<div class="legend">
*  : campo opzionale.<br/>
</div>
</div><!-- end #offer-hook -->

<?php
__skipDATA:

if($vars['isAdmin']){
// \Fprint::r($vars);

	// antepongo Genre- come aggancio al metodo isEnum della fowd/action/register che utilizzo per il controllo
	$options = array(
		"offerente" => "Offerente",
		"standard"	=> "Utente Regolare",
		"evaluating"=> "In valutazione"
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
	$fadd->createField('Genre','Imposta Genere Utente (ora &egrave; "' . $vars['Genre']. '")', 'input/dropdown', $vars);

	?>
	<div class="foowd-user-settings-admin">Ti ricordo che qualora l'utente giunga allo stato "offerente" partendo da un'altro genere, questi verr&agrave; informato tramite una mail</div>
	<?php

}else{

	echo elgg_view('input/hidden', array('name' => 'Genre', 'value' => $vars['Genre']));

}


elgg_require_js('foowd_utenti/foowd-user-settings-edit');
elgg_require_js('foowdFormCheck');