<?php


$path = \Uoowd\Param::pathStore(97,'profile');
\Fprint::r("file: ".$path);
foreach( new \DirectoryIterator($path) as $fileInfo){
	\Fprint::r($fileInfo->getPathname());
	// dentro directory file#
	if($fileInfo->isDir() && !$fileInfo->isDot() ){
		foreach(new \DirectoryIterator($fileInfo->getPathname()) as $file){
			if($file->isFile()){
				$dir = $file->getPath() . DIRECTORY_SEPARATOR;
				$newName = str_replace('file', '', $file->getFilename());
				\Fprint::r($file->getPathname() .' , ' . $dir.' & '.$newName);
				rename($file->getPathname(), $dir.$newName);
			}
			// dentro file#/small, big o medium
			if($file->isDir() && !$file->isDot() ){
				foreach(new \DirectoryIterator($file->getPathname()) as $f){
					if($f->isFile()){
						$dir = $f->getPath() . DIRECTORY_SEPARATOR;
						$newName = str_replace('file', '', $f->getFilename());
						\Fprint::r($f->getPathname() .' , ' . $dir.' & '.$newName);
						rename($f->getPathname(), $dir.$newName);
					}
				}	
			}
		}	
	
	}
}