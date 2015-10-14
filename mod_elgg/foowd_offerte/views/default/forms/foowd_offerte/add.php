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


$fadd->createField('Name', 'foowd:name:need', 'input/text');
$fadd->createField('Description', 'foowd:description:need', 'input/longtext');

// la prima volta non dovrebbe essere impostato niente, e visualizzo soltanto il form di caricamento
$fadd->createField('file', 'foowd:file:need', 'input/file', array('id'=>'loadedFile', 'value'=>''));
echo '<center><div id="image-container" style="display:none;">Seleziona l\'area da ritagliare.<div id="image"></div></div></center>';

$fadd->createField('Quota', 'foowd:quota:need', 'input/text', array('maxlength'=>"9"));
$fadd->createField('Unit','foowd:unit:need', 'input/select', $vars['_Unit']);
$fadd->createField('UnitExtra','foowd:unit:extra', 'input/text', array('maxlength'=>"30"));
?>
<label for="quota-preview"><?php echo elgg_echo('foowd:quota:preview'); ?></label>
<div id="quota-preview"></div>
<?php
$fadd->createField('Price','foowd:price:need', 'input/text', array('maxlength'=>"11"));
// $fadd->createField('Price', 'Importo *', 'input/spinner', array("decimal"=>2, "integer"=>"8"));
// i Tag hanno un metodo particolare
$fadd->createField('Tag', 'foowd:tag:need', 'input/checkbox', array('inputs' => $vars['Tag'], 'attributes' =>$vars['TagAttributes']) );
$fadd->createField('Minqt', 'foowd:minqt:need', 'input/text', array('maxlength'=>"9"));
// $fadd->createField('Minqt', 'Quantita\' minima *', 'input/spinner', array("decimal"=>3, "integer"=>5));
$fadd->createField('Maxqt', 'foowd:maxqt', 'input/text', array('maxlength'=>"9"));
// $fadd->createField('Maxqt', 'Quantita\' massima', 'input/spinner', array("decimal"=>3, "integer"=>5));
// $fadd->createField('Expiration', 'foowd:expiration', 'input/date', array('timestamp'=>true));
// echo elgg_view('input/date', array('timestamp'=>true, 'name'=>'lol', 'value'=>'1255'));

// il javascript e la struttura sono in FormAdd: hookCreateExpiration
$fadd->createField('Expiration', 'foowd:expiration', '', array('Expiration'=>$vars['Expiration']));

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
<link href="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css" rel="stylesheet">
<!-- <script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_offerte/js/imgareaselect/scripts/jquery.min.js"></script> -->
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>

<?php 
elgg_require_js(\Uoowd\Param::pid().'/use_crop'); 
elgg_require_js(\Uoowd\Param::pid().'/offer-form-check.amd'); 

// echo \Uoowd\Param::pid().'/use_crop';