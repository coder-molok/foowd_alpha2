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

// $fadd->createField('contract','condizioni', 'input/checkboxes', 
// 	array('internalname' => 'features' , 'options' => array("Original" => "Original" , "Unoriginal" => "Unorginal") )
// 	);

?>

<div id="offer-hook">
	<?php
		// $fadd = new \Foowd\Action\FormAdd($vars);
		
		// Descrizione utente
		// $fadd->createField('Description', 'foowd:user:description:need', 'input/longtext');

		
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

	<a href="<?php echo elgg_echo('foowd:image-tmp');?>" id="url" style="display:none;" >testo</a>

	<?php
	$fadd->createField('Owner','foowd:user:owner:need', 'input/text', array('maxlength'=>"100"));
	$fadd->createField('Piva','foowd:user:piva:need', 'input/text', array('maxlength'=>"11"));
	
	$fadd->createField('Company','foowd:user:company:need', 'input/text', array('maxlength'=>"100"));
	
	$fadd->createField('Site','foowd:user:site:optional', 'input/text', array('maxlength'=>"255"));
	$fadd->createField('Phone','foowd:user:phone:need', 'input/text', array('maxlength'=>"15"));

	echo elgg_view('login/address');
	elgg_require_js('foowd_utenti/user-register-address');

	$fadd->createField('MinOrderPrice','foowd:user:minorderprice:need', 'input/text', array('maxlength'=>"11"));

	?>

<div class="legend">
*  : campo opzionale.<br/>
** : campo obbligatorio per la registrazione come <strong>offerente</strong>.
</div>
</div><!-- end #offer-hook -->

<?php
$link = '<a href="' . \Uoowd\Param::page()->legalConditions .'" target="blank">link</a>';
?>
<div>
	<label for="contract">Condizioni generali </label><br>
	
	<div id="contract-standard">
		<input type="checkbox" name="contract" value="standard"/>
		<span class="accept">
			Accetto le <b>Condizioni generali d'utilizzo</b> e le <b>Condizioni specifiche</b> disponibili al seguente <?php echo $link; ?>, in particolare accetto le condizioni delle sezioni <span class="emph"> Generale e Disposizioni relative ai CapiGruppo</span>
		</span>
	</div>
	
	<div id="contract-offerente">
		<input type="checkbox" name="contract" value="offerente"/>
		<span class="accept">
			Accetto le <b>Condizioni generali d'utilizzo</b> e <b>le Condizioni specifiche</b> disponibili al seguente <?php echo $link; ?>, in particolare accetto le condizioni delle sezioni <span class="emph"> Generale e Disposizioni relative ai Professionisti</span>
		</span>
	</div>

</div>


<?php


echo '';

// elgg_require_js('foowd_utenti/file');
elgg_require_js('foowd_utenti/user-register');
elgg_require_js('foowdFormCheck');


$template = <<<__TEMPLATE

<div id="fileTmpl" style="display:none;">
<div id="file-num_par-hook">
	<div>
		<label for="file-num_par">Carica l'immagine *</label><div style="color:red"></div><br>
		<input name="file-num_par" value="" class="elgg-input-file" type="file" data-num="-num_par">
	</div>
	<center>
		<div id="file-num_par-container"></div>
	</center>
	<div class="crop">
		    <input name="crop_file-num_par[x1]" value="" type="hidden">
		    <input name="crop_file-num_par[y1]" value="" type="hidden">
		    <input name="crop_file-num_par[x2]" value="" type="hidden">
		    <input name="crop_file-num_par[y2]" value="" type="hidden">    
	</div>
</div>
</div>

__TEMPLATE;
?>