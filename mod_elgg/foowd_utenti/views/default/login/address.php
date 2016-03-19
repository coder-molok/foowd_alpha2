
<?php

// return; 

$fadd = new \Foowd\Action\Register($vars);

// $fadd->createField('Nation','foowd:user:nation:need', 'input/dropdown', array('default_value'=>'it', 'options_values'=> array('it')));

$fadd->createField('Region','foowd:user:region:need', 'input/dropdown', array('options_values'=> array('seleziona regione')));

$fadd->createField('Province','foowd:user:province:need', 'input/dropdown', array('options_values'=> array('seleziona provincia')));

$fadd->createField('City','foowd:user:city:need', 'input/dropdown', array('options_values'=> array('_none_'=>'seleziona comune')));

// $typ = array(
// 	'--', "via", "viale", 'corso', 'largo', 'piazza', 'piazzale', 'vicolo', 'frazione'
// );

// $add_typ = array();
// foreach ($typ as $v) {
// 	$key = ($v == '--')	? '_none_' : $v;
// 	$add_typ[$key] = $v;
// }


// $fadd->createField('AddressesType','foowd:user:addressestype:need', 'input/dropdown', array('options_values'=> $add_typ));

$fadd->createField('Address','foowd:user:address:need', 'input/text', array('maxlength'=>"150"));

// $fadd->createField('Civic','foowd:user:civic:need', 'input/text', array('maxlength'=>"150"));

$fadd->createField('Zipcode','foowd:user:zipcode:need', 'input/text', array('maxlength'=>"6"));

// $fadd->createField('Location','foowd:user:location:need', 'input/text', array('maxlength'=>"150"));

// creo un hook per l'inserimento automatico nel caso sia nel form dei settings
if( isset($vars['City']) && !preg_match('@_none@', $vars['City']) ) echo elgg_view('input/hidden', array('name' => 'cityValueHook', 'value' => $vars['City']));


/*
$fadd = new \Foowd\Action\Register();

echo "<table>";
echo "<tr>";

echo "<td>";
$fadd->createField('Nation','Nazione', 'input/dropdown', array('default_value'=>'it', 'options_values'=> array('it')));
echo "</td>";
echo "<td>";
$fadd->createField('Province','Provincia', 'input/dropdown', array('options_values'=> array('seleziona provincia')));
echo "</td>";
echo "<td>";
$fadd->createField('City','Comune', 'input/dropdown', array('options_values'=> array('seleziona comune')));
echo "</td>";

echo "</tr>";
echo "<tr>";

$add_typ = array(
	'--', "via", "viale", 'corso', 'largo', 'piazza', 'piazzale', 'vicolo', 'frazione'
);

echo "<td>";
$fadd->createField('Zipcode','CAP', 'input/text', array('maxlength'=>"5"));
echo "</td>";
echo "<td>";
$fadd->createField('addresses_type','Tipo', 'input/dropdown', array('options_values'=> $add_typ));
echo "</td>";
echo "<td>";
$fadd->createField('Address','foowd:user:user:address:need', 'input/text', array('maxlength'=>"150"));
echo "</td>";
echo "<td>";
$fadd->createField('Civic','Civico', 'input/text', array('maxlength'=>"150"));
echo "</td>";

echo "</tr>";
echo "</table>";

$fadd->createField('Location','Localit&agrave;', 'input/text', array('maxlength'=>"150"));
*/
