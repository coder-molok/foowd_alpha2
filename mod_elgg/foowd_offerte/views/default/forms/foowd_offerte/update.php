<?php
$form = \Uoowd\Param::pid().'/update';
//elgg_make_sticky_form($form);
?>

<?php
// /views/default/input/

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\Action\FormAdd($vars);

// var_dump($_SESSION['sticky_forms']);

$fadd->createField('Name', 'Offerta', 'input/text');
$fadd->createField('Description', 'Descrivi il tuo prodotto', 'input/longtext');
// $fadd->createField('Price','Importo', 'input/text', array('maxlength'=>"11"));
$fadd->createField('Price', 'Import', 'input/spinner', array("decimal"=>2, "integer"=>"8"));
$fadd->createField('Tag', 'Tags (singole parole separate da una virgola)', 'input/text');
// $fadd->createField('Minqt', 'Quantita\' minima', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Minqt', 'Quantita\' minima', 'input/spinner', array("decimal"=>3, "integer"=>5));
// $fadd->createField('Maxqt', 'Quantita\' massima', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Maxqt', 'Quantita\' massima', 'input/spinner', array("decimal"=>3, "integer"=>5));

?>

<div>
    <input type="hidden" name="Id" value="<?php echo $vars['Id']; ?>" />
</div>
<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>
<?php

