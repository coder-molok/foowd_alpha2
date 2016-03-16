<?php
$fadd = new \Foowd\Action\Register();

$fadd->createField('file', 'foowd:file:need', 'input/file', array('id'=>'loadedFile', 'value'=>''));

// div image se esiste img
$dir = \Uoowd\Param::imgStore().'User-'.$vars['guid'].'/avatar';
// echo $dir;
$style = 'style="display:none;"';

if(file_exists($dir)){
	foreach( new \DirectoryIterator($dir) as $single){
		// non faccio controlli particolari per ora
		if($single->isFile() && $single->getExtension() !== 'json'  ){
		 	$img = $single->getPathname();
		 	// break;
		 }elseif($single->getExtension() === 'json'){
		 	$oldCrop = json_decode( file_get_contents($single->getPathname()) );
		 }
	}
	if($img) {

		$img = str_replace('\\','/', $img);
		$path = $img;
		$type = pathinfo($img, PATHINFO_EXTENSION);
		$img = file_get_contents($img);
		list($width, $height, $type, $attr) = getimagesize($path);
		$hOw = $height/$width;
		$w = 400;
		$h = $w * $hOw;
		$img = 'data:image/' . $type . ';base64,' . base64_encode($img);
		$img = "<img src=\"$img\" width=\"{$w}px\" height=\"{$h}px\"/>";
		$style = '';
	}
}
echo '<center><div id="image-container" '.$style.' >';

echo sprintf( "<script>document.write(\"%s\");</script>", elgg_echo('foowd:image:cut:area') );

?>
<noscript>Javascript disattivato: <br/> visualizzerai la nuova immagine dopo il salvataggio.</noscript>
<?php

echo '<div id="image">'.$img.'</div></div></center>';
?>

<div>
    <input type="hidden" name="Id" value="<?php echo $vars['Id']; ?>" />
</div>

<div class="elgg-foot">
    <?php 
        // la guid mi serve per salvare il file temporaneo
        echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
        if(isset($vars['sticky'])) echo elgg_view('input/hidden', array('name' => 'sticky', 'value' => $vars['sticky'])); 
    ?>
</div> 
<div id="crop">
    <input type="hidden" name="crop[x1]" value="<?php echo $oldCrop->x1; ?>" />
    <input type="hidden" name="crop[y1]" value="<?php echo $oldCrop->y1; ?>" />
    <input type="hidden" name="crop[x2]" value="<?php echo $oldCrop->x2; ?>" />
    <input type="hidden" name="crop[y2]" value="<?php echo $oldCrop->y2; ?>" />    
</div>
<a href="<?php echo elgg_get_site_url().elgg_echo('foowd:image-tmp');?>" id="url" style="display:none;" >testo</a>

<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>

<link href="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>

<?php

// elgg_require_js('foowd_utenti/file');
// elgg_require_js('foowd_utenti/avatar');
elgg_require_js('foowd_utenti/avatar-crop'); 
