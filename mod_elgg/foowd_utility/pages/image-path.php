<?php
header('Content-Type: application/json; charset=utf-8');

function detectRequestBody() {
    $rawInput = fopen('php://input', 'r');
    $tempStream = fopen('php://temp', 'r+');
    stream_copy_to_stream($rawInput, $tempStream);
    rewind($tempStream);

    return $tempStream;
}


$par = 'ExternalId';

if(!isset($_POST[$par])){
 	$entityBody = stream_get_contents(detectRequestBody());
	$data = json_decode($entityBody);
	$data = $data->{$par};
}else{
	$data = $_POST[$par];
}



$dirImg = rtrim(\Uoowd\Param::userStore($data),'/');

$imgAr = array();

function trovaImg($root, &$img, $dir=null, $deep = null){
	if(is_null($deep)) $deep =0;
	if(is_null($dir)) $dir = $root;
	$deep++;
	$iter = new \DirectoryIterator($dir);
	foreach($iter as $file){
		if($file->isDir()){
			if(!$file->isDot()) trovaImg($root, $img , $file->getPathname(), $deep);
		}else{

			if($file->getExtension() === 'json') continue;

			$f = str_replace($root, '', $file->getPathname());
			$f = trim($f, DIRECTORY_SEPARATOR);
			$fe = explode( DIRECTORY_SEPARATOR , $f);

			// var_dump($fe[0]);
			// var_dump($f);

			$i = $fe[0];
			if($i === 'avatar') $img['avatar'][] = $f;
			if($i === 'profile') $img['profile'][] = $f;
			if($i === 'offers') $img['offers'][] = $f;
		}
		
	}
}

trovaImg($dirImg, $imgAr);

echo json_encode($imgAr);