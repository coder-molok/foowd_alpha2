<?php
// /views/default/input/

$form = \Uoowd\Param::pid().'/add';

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\Action\FormAdd($vars);

//for rapid testing
// $api = new \Foowd\API();
// $ar['publisher']=elgg_get_logged_in_user_guid();
// $ar['name']="cassa di mana";
// $ar['description']="Questo e' un prodotto da veri nerd...";
// $ar['tags']="fantasy, adventure, latte, miele";
// $ar['price']='100,59';
// if($api){
// 	$api->Create('offer', $ar);
// 	$r = $api->stop();
// 	if($r->response) var_dump($r);
// }

// $api = new \Foowd\API();
// $ar['publisher']=elgg_get_logged_in_user_guid();
// $ar['name']="Formaggi!";
// $ar['description']="roba buona!";
// $ar['tags']="latte, adventure, cibo, vita";
// $ar['price']='100,59';
// if($api){
// 	$api->Create('offer', $ar);
// 	$r = $api->stop();
// 	if($r->response) var_dump($r);
// }

// var_dump($_SESSION['sticky_forms']);
//var_dump($_SESSION['my']);

// $fadd->createField('Name', 'Offerta *', 'input/text');
// $fadd->createField('Description', 'Descrivi il tuo prodotto *', 'input/longtext');
// $fadd->createField('Price','Importo *', 'input/text', array('maxlength'=>"11"));
// $fadd->createField('Tag', 'Tags (singole parole separate da una virgola) *', 'input/text');
// $fadd->createField('Minqt', 'Quantita\' minima *', 'input/text', array('maxlength'=>"9"));
// $fadd->createField('Maxqt', 'Quantita\' massima', 'input/text', array('maxlength'=>"9"));

$fadd->createField('Name', 'Offerta *', 'input/text');
$fadd->createField('Description', 'Descrivi il tuo prodotto *', 'input/longtext');
// $fadd->createField('Price','Importo', 'input/text', array('maxlength'=>"11"));
$fadd->createField('Price *', 'Importo *', 'input/spinner', array("decimal"=>2, "integer"=>"8"));
$fadd->createField('Tag', 'Tags (singole parole separate da una virgola) *', 'input/text');
// $fadd->createField('Minqt', 'Quantita\' minima', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Minqt', 'Quantita\' minima *', 'input/spinner', array("decimal"=>3, "integer"=>5));
// $fadd->createField('Maxqt', 'Quantita\' massima', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Maxqt', 'Quantita\' massima', 'input/spinner', array("decimal"=>3, "integer"=>5));

?>

<!-- <div>
    <label><?php echo elgg_echo("Offerta"); ?></label><div style="color:red"><?php echo elgg_echo($fadd->nameError);?></div><br />
    <?php echo elgg_view('input/text',array('name' => 'name', 'value' => elgg_echo($fadd->name)) ); ?>
</div>

<div>
    <label><?php echo elgg_echo("descrizione"); ?></label><br />
    <?php echo elgg_view('input/longtext',array('name' => 'description' ,'value' => elgg_echo($fadd->description)) ); ?>
</div>

<div>
    <label><?php echo elgg_echo("importo"); ?></label><div style="color:red"><?php echo elgg_echo($fadd->priceError);?></div><br />
    <?php echo elgg_view('input/text',array('name' => 'price',  'maxlength' => 20, 'value' => elgg_echo($fadd->price)) ); ?>
</div>

<div>
    <label><?php echo elgg_echo("tags"); ?></label><br />
    <?php echo elgg_view('input/tags',array('name' => 'tags', 'value' => elgg_echo($fadd->tags)) ); ?>
</div>
 -->
<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<div>
    <?php echo '* : campo obbligatorio.'; ?>
</div>

<?php
