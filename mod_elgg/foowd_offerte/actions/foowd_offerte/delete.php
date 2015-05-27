<?php

gatekeeper();


$data['Publisher']=elgg_get_logged_in_user_guid();
$data['Id']=(int)get_input('Id');
$data['type']='delete';


$r = \Uoowd\API::Request('offer','POST', $data);


// se sono qui la validazione lato elgg e' andata bene
// ma ora controllo quella lato API remote
if($r->response){
	system_message(elgg_echo("eliminato il post ".$data['Id']));

	// elimino anche la directory che contiene i suoi files
	$dir = str_replace('\\', '/', \Uoowd\Param::imgStore());
	$dir .= 'User-'.elgg_get_logged_in_user_guid().'/';
	$dir = $dir.$data['Id'].'/';
	// register_error($dir);
	unlinkDir($dir);

}else{
	// nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
	if(! $str = \Uoowd\Param::dbg()){ 
		$str = "errore nell'eliminazione di offerta numero ".$data['Id'];
	}
	register_error($str);
}


forward(REFERER);

function unlinkDir($dir){
	// if(!file_exists($dir)) return;
	foreach (new \DirectoryIterator($dir) as $fileInfo) {
		// se e' dot la ignoro
		if($fileInfo->isDot()) continue;

		if($fileInfo->isDir()  && !rmdir($fileInfo->getPathname()) ){
			unlinkDir($fileInfo->getPathname());
		} 

	    unlink($fileInfo->getPathname());

	}

	rmdir($dir);
}