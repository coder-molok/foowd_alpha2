<?php

namespace Uoowd;

/**
 * classe adibita al controllo delle dipendenze di plugins
 */

class FoowdNeedleDependencies{

	public $plugList = array(
			'search' => 'svolgere una ricerca, in base a quando stabilito in FoowdSearch (questo wrappa le normali ricerche di foowd)', 
			'friend_request' => 'controllare e mandare richieste di amicizia in stile facebook, tutto comodamente dalla sidebar',
			'invitefriends' => 'visualizzare la scelta \'invita amico\' nella sidebar, con conseguente form per invio mail'
		);

	public function __construct(){

		if( !elgg_is_admin_logged_in() ) return;

		$str = "Plugin ~ %s ~ %s!!!<br/> \n si prega di attivarlo: <br/>\n Questo plugin si utilizza per %s \n";

		foreach ($this->plugList as $key => $value) {
			$error = false;
			$plugN = $key;
			$plug = elgg_get_plugin_from_id($plugN);

			if(is_null($plug)){
				$error = true;
				$status = 'non presente';
			}elseif(!$plug->isActive()) {
				$error = true;
				$status = 'disattivato';
			}
			
			if($error){
			    // \Fprint::r('plugin non attivo');
			    register_error( sprintf($str, $key, $status, $value) );
			}
		}

	}// end __construct

}