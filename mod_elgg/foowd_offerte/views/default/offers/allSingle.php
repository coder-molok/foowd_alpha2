<?php 

// var_dump($vars['single']);

$of = $vars['single'];



// div image se esiste img
$dir = \Uoowd\Param::imgStore().'User-'.$vars['guid'].'/'.$of['Id'].'/small/';
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
		$img = 'data:image/' . $type . ';base64,' . base64_encode($img);
		$img = '<img src="'.$img.'" />';
		$style = '';
	}
}else{
	// echo "something wrong...";
}


// ora penso ai bottoni;
$str = '';

$str.= elgg_view('output/url', array(
		// associate to the action
		'href' => elgg_get_site_url() . "action/".$vars['pid']."/delete?Id=" . $of['Id'],
	    'text' => elgg_echo('elimina: '.$of['Id']),
	    'is_action' => true,
	    'is_trusted' => true,
	    'confirm' => elgg_echo('Sei sicuro di voler eliminare questa offerta: '.$of['Id']),
	    'class' => 'elgg-button elgg-button-delete',
    ));//."\n\r<br/><br/><br/>";
$str.= elgg_view('output/url', array(
		// associate to the action
		'href' => elgg_get_site_url() . $vars['pid'] ."/single?Id=" . $of['Id'],
	    'text' => elgg_echo('modifica: '.$of['Id']),
	    //'is_action' => true,
	    //'is_trusted' => true,
	    //'confirm' => elgg_echo('deleteconfirm'),
	    'class' => 'elgg-button elgg-button-delete',
    ))."\n\r<br/><br/><br/>";



//  tronco ai primi 100 caratteri, ma senza troncare l'ultima parola
$your_desired_width =  150;
$string= $of['Description'];
if (strlen($string) > $your_desired_width){
    $string = wordwrap($string, $your_desired_width);
    $string = substr($string, 0, strpos($string, "\n"));
    $string .= ' [...]';
}

$of['Description'] = $string;


?>


<table class="foowd-all-single-container"><!-- single container -->
	<tr>
	<td class="foowd-all-img"><?php echo $img; ?></td>
	<td class="foowd-all-body">
		<div class="foowd-title"><?php echo $of['Name']; ?></div>
		<div class="foowd-description"><?php echo $of['Description']; ?></div>
	</td>
	<td class="foowd-all-data">
		<div class="">Prezzo:<div class="foowd-data"><?php echo $of['Price']; ?></div></div>
		<div class="">Quantit&agrave; Totale:<div class="foowd-data"><?php echo $of['totalQt']; ?></div></div>
	</td>
	<td class="foowd-all-data">
		<div class="">Data Creazione:<div class="foowd-data"><?php echo $of['Created']; ?></div></div>
		<div class="">Ultima Modifica:<div class="foowd-data"><?php echo $of['Modified']; ?></div></div>
	</td>
	</tr>
</table><!-- close single container -->


<?php 

	echo $str;

