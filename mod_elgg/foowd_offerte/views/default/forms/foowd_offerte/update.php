<?php
$form = 'foowd_offerte/update';
//elgg_make_sticky_form($form);
?>

<?php
// /views/default/input/

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\Action\FormAdd($vars);

// var_dump($_SESSION['sticky_forms']);

$fadd->createField('name', 'Offerta', 'input/text');
$fadd->createField('description', 'Descrivi il tuo prodotto', 'input/longtext');
$fadd->createField('price','Importo (cifre con virgola)', 'input/text');
$fadd->createField('tags', 'Tags (singole parole separate da una virgola)', 'input/text');
?>

<div>
    <input type="hidden" name="id" value="<?php echo $vars['id']; ?>" />
</div>
<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>
<?php

