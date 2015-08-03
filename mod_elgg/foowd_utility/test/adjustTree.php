<?php

$dir = __DIR__;

define('DS', DIRECTORY_SEPARATOR);

// $store = 'FoowdStore';
// $info = pathinfo($dir);

// if( $info['basename'] !== $store){
// 	rename($info['dirname'].DS.$info['basename'] , $info['dirname'].DS.$store);
// }


// rename()

$iter = new DirectoryIterator($dir);

foreach($iter as $userDir){
	if($userDir->isDir() && !$userDir->isDot()){
		$curDir = $userDir->getPathname();
		$offer = $curDir.DS.'offers';
		if(!file_exists( $offer )){
		 	mkdir( $offer );
		 var_dump( $offer );
		}
		adjustTree($curDir);
	}
}

function adjustTree($dir){
	$iter = new DirectoryIterator($dir);
	foreach($iter as $d){
		if($d->isDir() && !$d->isDot()){
			// var_dump($dir);
			$folder = $d->getBasename();
			if($folder === 'avatar' || $folder === 'profile') continue;
			if(is_numeric($folder)){
				$src = $d->getPathname();
				// var_dump($src);
				$dst = $d->getPath().DS.'offers'.DS.$folder;
				rename($src, $dst);
			} 
		}
	}
}