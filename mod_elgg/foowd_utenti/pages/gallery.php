<?php

ob_start();
// var_dump($_SESSION['sticky_forms']['foowd-avatar']);
unset($_SESSION['sticky_forms']['foowd-gallery']);

$vars['guid']=elgg_get_logged_in_user_guid();


// echo elgg_view_form('foowd-gallery', array('enctype'=>'multipart/form-data'), $vars);



$dir = \Uoowd\Param::pathStore($vars['guid'],'profile');

if(!file_exists($dir)){
	if(!mkdir($dir)) echo 'impossibile creare la directory';
}



echo '<div id="gallery-container">';

$iter = new DirectoryIterator($dir);
foreach ($iter as $key => $value) {
	// echo $iter->getPathname().'<br/>';
	if($iter->isDir() && !$iter->isDot()){
		$path = pathinfo($iter->getPathname());
		$name = $path['basename'];
		$baseUrl = \Uoowd\Param::pathStore($vars['guid'],'profile','host').$name.'/';
		$num = str_replace('file','', $name);
		$img = str_replace('\\', '/', $baseUrl.'medium/'.$num.'.jpg' );

		$host = str_replace('\\', '/', $baseUrl.$num.'.jpg' );

		$original =str_replace('\\', '/', $iter->getPathname().'/'.$num.'.jpg');
		?>
		<div class="hook" data-src="<?php echo $img;?>" data-original="<?php echo $original;?>" data-host="<?php echo $host;?>" style="display:none;"></div>
		<?php
	}
}

echo '</div>'; // chiudo gallery-container



// elgg_require_js('foowd_utenti/file');
elgg_require_js('foowd_utenti/user-gallery');
?>
<link href="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo elgg_get_site_url ();?>mod/foowd_utility/js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>

<a href="<?php echo elgg_get_site_url().elgg_echo('foowd:image-profile')?>" id="url" style="display:none;" >testo</a>
<div class="guid" data-num="<?php echo $vars['guid'];?>" style="display:none;"></div>
<div class="file-box-hook"></div>


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
		    <input name="crop_file-num_par[x1]" data-crop='x1' value="" type="hidden">
		    <input name="crop_file-num_par[y1]" data-crop='y1' value="" type="hidden">
		    <input name="crop_file-num_par[x2]" data-crop='x2' value="" type="hidden">
		    <input name="crop_file-num_par[y2]" data-crop='y2' value="" type="hidden">    
	</div>
</div>
</div>



<div id="imgTmpl" style="display:none;">
<div class="single">
	<img src="_imgSource" data-original="_imgOriginal" data-host="_imgHost" data-id="_imgId">
	<a class="delete elgg-button">Elimina</a>
	<a class="change elgg-button">Ritaglia</a>
</div>
</div>

<?php

$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-gallery">'.$body.'</div>';

echo elgg_view_page('Gallery',$body);