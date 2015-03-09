<?php
// /views/default/input/

$form = 'foowd_offerte/add';

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\FormAdd($vars);

?>

<div>
    <label><?php echo elgg_echo("titolo"); ?></label><div style="color:red"><?php echo elgg_echo($fadd->titleError);?></div><br />
    <?php echo elgg_view('input/text',array('name' => 'title', 'value' => elgg_echo($fadd->title)) ); ?>
</div>

<div>
    <label><?php echo elgg_echo("descrizione"); ?></label><br />
    <?php echo elgg_view('input/longtext',array('name' => 'description' ,'value' => elgg_echo($fadd->description)) ); ?>
</div>

<div>
    <label><?php echo elgg_echo("importo"); ?></label><div style="color:red"><?php echo elgg_echo($fadd->importError);?></div><br />
    <?php echo elgg_view('input/text',array('name' => 'import',  'maxlength' => 20, 'value' => elgg_echo($fadd->import)) ); ?>
</div>

<div>
    <label><?php echo elgg_echo("tags"); ?></label><br />
    <?php echo elgg_view('input/tags',array('name' => 'tags', 'value' => elgg_echo($fadd->tags)) ); ?>
</div>
<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<?php
