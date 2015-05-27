<?php
// /views/default/input/

// $form = \Uoowd\Param::pid().'/add';

// per test rapidi
// $vars['Name']='jkjj';
// $vars['Description'] = 'kkkkkk';
// $vars['Price-integer'] = '3';
// $vars['Tag'] = 'jjjjjjj';
// $vars['Minqt-integer'] = '4';
// $vars['Price'] = '3.0';
// $vars['Minqt'] = '4.0';

// utilizzo questa classe per maneggiare le variabili e lo sticky_form
// gli Error servono per generare il messaggio di errore dentro al form
$fadd = new \Foowd\Action\FormAdd($vars);


$fadd->createField('Name', 'Offerta *', 'input/text');
$fadd->createField('Description', 'Descrivi il tuo prodotto *', 'input/longtext');

// la prima volta non dovrebbe essere impostato niente, e visualizzo soltanto il form di caricamento
$fadd->createField('file', 'Carica l\'immagine', 'input/file', array('id'=>'loadedFile', 'value'=>''));
echo '<center><div id="image-container" style="display:none;">Seleziona l\'area da ritagliare.<div id="image"></div></div></center>';

// $fadd->createField('Price','Importo', 'input/text', array('maxlength'=>"11"));
$fadd->createField('Price', 'Importo *', 'input/spinner', array("decimal"=>2, "integer"=>"8"));
$fadd->createField('Tag', 'Tags (singole parole separate da una virgola) *', 'input/text');
// $fadd->createField('Minqt', 'Quantita\' minima', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Minqt', 'Quantita\' minima *', 'input/spinner', array("decimal"=>3, "integer"=>5));
// $fadd->createField('Maxqt', 'Quantita\' massima', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Maxqt', 'Quantita\' massima', 'input/spinner', array("decimal"=>3, "integer"=>5));

?>


<div class="elgg-foot">
    <?php 
        // la guid mi serve per salvare il file temporaneo
        echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
        if(isset($vars['sticky'])) echo elgg_view('input/hidden', array('name' => 'sticky', 'value' => $vars['sticky'])); 
    ?>
</div> 
<div id="crop">
    <input type="hidden" name="crop[x1]" value="" />
    <input type="hidden" name="crop[y1]" value="" />
    <input type="hidden" name="crop[x2]" value="" />
    <input type="hidden" name="crop[y2]" value="" />    
</div>
<a href="image-tmp" id="url" style="display:none;">testo</a>

<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<div>
    <?php echo '* : campo obbligatorio.'; ?>
</div>


<?php elgg_load_js('jquery'); ?>
<link href="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/css/imgareaselect-default.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/scripts/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
<?php elgg_require_js(\Uoowd\Param::pid().'/use_crop'); 
// echo \Uoowd\Param::pid().'/use_crop';