<?php

/**
 * Richiamato in  forms/account/settings.php di foowd_utenti
 */

// questi due sono hook che uso per la validazione in javascript
echo elgg_view('input/hidden', array('name' => 'hookUsernameBefore', 'value' => $vars['username']));
echo elgg_view('input/hidden', array('name' => 'hookEmailBefore', 'value' => $vars['Email']));
echo elgg_view('input/hidden', array('name' => 'hookEmailToSet', 'value' => $vars['emailToSet']));

if($vars['emailToSet'] != '' ){ echo '<div class="foowd-user-settings-admin-evaluating">Stiamo aspettando la conferma della mail inviata all\'indirizzo <b>' .$vars['emailToSet'] .'</b> .<br/>Una volta cofermata verr&aacute; Aggiornato il campo.</div>';}
// \Fprint::r($vars['username']);

$fadd = new \Foowd\Action\UserSave($vars);

// \Fprint::r($vars);

// a prescindere, il campo username lo inserisco
$fadd->createField('Username',elgg_echo('username'), 'input/text', array('value'=> $vars['username']));


if(!$vars['isAdmin'] && $vars['Genre'] == 'standard') goto __skipDATA;
$disable = ($vars['Genre'] == 'offerente' && !$vars['isAdmin']);

?>
<div id="offer-hook">
	<?php

		

	// $fadd->createField('Description', 'foowd:user:description', 'input/longtext');
	$fadd->createField('Owner','foowd:user:owner', 'input/text', array('maxlength'=>"100", 'disabled'=>$disable));
	$fadd->createField('Piva','foowd:user:piva', 'input/text', array('maxlength'=>"11", 'disabled'=>$disable));
	$fadd->createField('Company','foowd:user:company', 'input/text', array('maxlength'=>"100", 'disabled'=>$disable));
	
	$fadd->createField('Site','foowd:user:site:optional', 'input/text', array('maxlength'=>"255"));
	$fadd->createField('Phone','foowd:user:phone', 'input/text', array('maxlength'=>"15"));
	
	echo elgg_view('login/address', $vars);
	elgg_require_js('foowd_utenti/foowd-user-settings-address');

	$fadd->createField('MinOrderPrice','foowd:user:minorderprice:need', 'input/text', array('maxlength'=>"11"));
	
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