<?php

function generate(){

	$guid = get_input('guid');
	
	$prefix = 'file';
	$ar['response'] = true;
	try{
		$profile = \Uoowd\Param::pathStore($guid, 'profile');
		$iter = new DirectoryIterator($profile);
	}catch(\Exception $e){
		$ar['response'] = false;
		$ar['msg'] = 'Dentro a catch nella ricerca di '.$profile;
		// echo json_encode(array('response'=>false));
		return $ar;
	}
	
	
	$list = array(0);
	
	foreach ($iter as $f) {
		$name = $f->getBasename();
		if(strpos($name, $prefix) !== false ) array_push($list, str_replace($prefix, '', $name));
	}

	$m = max($list) ? max($list) : 0;
	$Fname = $prefix.++$m;
	$targetDir = $profile.$Fname;

	$ar['targetDir'] = $targetDir;
	$ar['dirName'] = $Fname;
	$ar['fname'] = $m;
	$ar['profileHost'] = \Uoowd\Param::pathStore($guid,'profile','host');
	
	if(!file_exists($targetDir)){
		if(!mkdir($targetDir)){
			$ar['response'] = false;
			$ar['msg'] = 'errore nella creazione della directory '.$targetDir;
			$ar['response'] = false;
			echo json_encode($ar);
		}
	}

	
	return $ar;
}


function checkNeedle($needle){
	foreach ($needle as $value) {
		if(is_null(get_input($value))){
			$error[]= $value;
		}
	}

	if(!empty($error)){
		$j['response'] = false;
		$j['msg'] = 'Mancano i parametri : '.implode($error,' , ');
		echo json_encode($j);
		exit;
	}
}


$check = false;


////////////////////////////////// saveFile
if(get_input('action') !== 'saveFile') goto saveFileEnd;
$check = true;
$count = 0;
foreach($_FILES as $file){
	$count ++;
	$r = generate();

	if(!$r['response']){
		unlink($r['targetDir']);
		echo json_encode($r);
		exit;
	}

	$target_file = $r['targetDir'].'/'.$r['fname'].'.'.pathinfo($file["name"], PATHINFO_EXTENSION);
	$r['targetFile']=$target_file;
	$r['ext']=pathinfo($file["name"], PATHINFO_EXTENSION);
	$r['name'] = $r['fname'].'.'.$r['ext'];

	if (move_uploaded_file($file["tmp_name"], $target_file)) {
		$r['preSrc'] = 'data:'.$file['type'].';base64,';
		$r['src'] = base64_encode(file_get_contents($target_file));
		$r['message'] = "File ". basename( $target_file). " salvato con successo.";
 		$r['response'] = true;
 	}else{
 		$r['response']=false;
 		$r['msg']='problema nel move_upload';
 	}
 	
}

if($count == 0){
	$r['response']=false;
	$r['msg']='probabilmente non e\' arrivato alcun file';
}

echo json_encode($r);
saveFileEnd:


/////////////////////////////// cropFile
if(get_input('action') !== 'cropFile') goto cropFileEnd;
$check = true;

$j['response'] = false ;

# src "C:/wamp/www/ElggProject/FoowdStorage/User-37/profile/file12/12.jpg"
# x1: 79, x2: 321, y1: 0, y2: 242
# http://localhost/ElggProject/elgg-1.10.5/foowd_utility/image-profile?action=cropFile&src=C:/wamp/www/ElggProject/FoowdStorage/User-37/profile/file12/12.jpg&x1=0&x2=0.321&%20y1=0&%20y2=0.242
$needle = array("src", "x1","x2","y1","y2");

checkNeedle($needle);

foreach ($needle as $value) {
	${$value} = get_input($value);
}

$crop['x1'] = $x1;
$crop['x2'] = $x2;
$crop['y1'] = $y1;
$crop['y2'] = $y2;

$info = pathinfo($src);

$cropCl = new \Uoowd\FoowdCrop();
$cropCl->target = $src;
$cropCl->saveDir = rtrim($info['dirname'],'/').'/';
$cropCl->cropSize = $crop;

$cropCl->crop();

$j['response'] = $cropCl->status;

echo json_encode($j);
exit;
cropFileEnd:



/////////////////////////////// removeDir
if(get_input('action') !== 'removeDir') goto removeDirEnd;
$check = true;
$j['response']=false;

$needle = array('subdir' , 'src', 'guid');
checkNeedle($needle);
foreach ($needle as $value) {
	${$value} = get_input($value);
}

if($subdir === "profile"){

	$profile = \Uoowd\Param::pathStore($guid, 'profile');

	// $j['src'] = $src;
	// $j['profile'] = $profile;

	// ottengo la directory base dell'immagine
	$dir = str_replace($profile, '', $src);
	$dir = explode('/', $dir);
	$dir = $dir[0];

	// ora scrivo la directory che realmente devo cancellare
	$dir = $profile.$dir;

	// NB: il passaggio e' tortuoso, ma cosi' garantisco di cancellare soltanto directory ammesse
	$base = preg_replace('@^\.\.@','' , \Uoowd\Param::page()->foowdStorage);
	if(strpos($dir, $base) !== false){


		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
		             RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
		    if ($file->isDir()){
		        rmdir($file->getRealPath());
		    } else {
		        unlink($file->getRealPath());
		    }
		}
		rmdir($dir);

		if(!file_exists($dir)){
			$j['response'] = true;
		}else{
			$j['msg'] = 'Errore di concellazione col recusriveDirectoryIterator. Directory: '.$dir;
		}


	}else{
		$j['msg'] = 'Attenzione, la directory che stai provando a cancellare non convince: '.$dir;
	}

}



echo json_encode($j);
exit;
removeDirEnd:



/////////////////////////////// removeDir
if(get_input('action') !== 'updateFile') goto updateFileEnd;
$check = true;
$j['response']=false;

$needle = array("src", "x1","x2","y1","y2");
checkNeedle($needle);
foreach ($needle as $value) {
	${$value} = get_input($value);
}

$crop['x1'] = $x1;
$crop['x2'] = $x2;
$crop['y1'] = $y1;
$crop['y2'] = $y2;

$info = pathinfo($src);

$cropCl = new \Uoowd\FoowdCrop();
$cropCl->target = $src;
$cropCl->saveDir = rtrim($info['dirname']).'/';
$cropCl->cropSize = $crop;

$cropCl->crop();

$j['response'] = $cropCl->status;

echo json_encode($j);
exit;

updateFileEnd:





///////////////////////////////// DEFAULT


$j['response'] = false;
if( !is_null(get_input('action')) ){
	$j['msg'] = "Il parametro 'action' non corrisponde ad alcuna azione valida";
}else{
	$j['msg'] = "Non hai fornito il parametro 'action' ";
}

if(!$check) echo json_encode($j);


exit(0);
