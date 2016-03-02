<?php

/**
 * file di richiamo a HyAuth
 */

namespace Foowd;

require_once(elgg_get_plugins_path().\Uoowd\Param::pid().'/vendor/autoload.php');

//use \Hybrid_Exception as Hybrid_Exception;


/**
 * Classe per l'implementazione di hybrid_auth
 */
class HyAuth{

	/**
	 * array di url 
	 * @var $base_urls
	 */
	private $base_urls = array();

	/**
	 * istanza classe HybridAuth
	 * @var [type]
	 */
	private $hybridauth;

	/**
	 * array contenente la data unix (time()) dell'ultimo aggiornamento relativo ai permessi delle app.
	 * @var array
	 */
	private $lastUpdatePermissions = array(
		"Facebook" => 1447064818,
		"Google" => 1447064818
	);

	/**
	 * imposto le configurazioni e setto un'istanza della classe HybridAuth.
	 * 
	 */
	public function __construct(){
		
		// se non e' un IP, eseguo il redirect:
		// questo perche' a google non piacciono gli IP
		$this->autoRedirect();
		
		// setConfig and instantiate HyAuth class
		$this->setConfig();



	}// end __construct()


	private function autoRedirect( $force = false){
		$actualUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		// echo $actualUrl;
		if(!filter_var($_SERVER['SERVER_NAME'], FILTER_VALIDATE_IP)){
			$regex = '@((\d{1,3}\.){3,}\d{1,3}).*@';
			$ip = preg_replace($regex, '$1', elgg_get_site_url());
			$actualUrl = $ip . $_SERVER['REQUEST_URI'];
			// echo $actualUrl;
			// \Uoowd\Logger::addError('Risultato '. $actualUrl);
			header('Location: '.$actualUrl, true, 302);
			exit;
		} 

		if($force){
			header('Location: '.$actualUrl, true, 302);
			exit;	
		}
	}

	/**
	 * ritorno istanza della classe hybridAut (utile per chiamare i metodi statici)
	 * @return [type] [description]
	 */
	public function getHybridAuth(){
		return $this->hybridauth;
	}


	/**
	 * prova a autenticarsi con un provider, e in caso di successo ritorna il rispettivo adapter
	 * @param  string $provider  	provider del quale voglio ottenere le info
	 * @return [type]           [description]
	 */
	public function getAdapter($provider, $elggUser = null){

		$user = (!is_null($elggUser)) ? $elggUser : elgg_get_logged_in_user_entity();

		try{

			$metaDate = 'auth'.$provider.'LastUpdate';
			
			// forzo per via del redirect url
			$this->setConfig($provider);
			$adapter =  $this->makeAuthentication($provider);
			
			// provvedo al logout dell'adapter per rimandare alla conferma dell'aggiornamento dei permessi,
			// qualora questi siano necessari. altrimenti non visualizza alcuna facciata intermedia.
			if(!isset($user->{$metaDate}) || $user->{$metaDate} < $this->lastUpdatePermissions[$provider] ){
				\Uoowd\Logger::addError('inside');
				$user->{$metaDate} = time();
				// essendo un metadato non ho bisogno di salvarlo
				// $user->save();
				$adapter->logout();
				$this->autoRedirect(true);
			}

			/**
			 * in questo blocco posso eventualmente aggiungere dei controlli...
			 * per il momento mi limito ad associare all'utente il "suo" id sulla social app
			 */
		    $metaId = 'auth'.$provider.'UserId';
		    $providerId = $adapter->getUserProfile()->identifier;
		    $user->{$metaId} = $providerId;

		    return $adapter;

		}catch(\Exception $e){

		  if(isset($adapter)){
		    // \Uoowd\Logger::addError('ti slogghi');
		    $adapter->logout();
		  } 
		  // impongo il refresh della pagina dopo il logout
		  // $page = '';//$_SERVER['PHP_SELF'];
		  // $sec = "0";
		  // header("Refresh: $sec");
		}

	}// get provider data

	// attempting authentication
	protected function makeAuthentication($provider){
		try{
			$adapter = $this->hybridauth->authenticate($provider);
			return $adapter;
		}catch(\Exception $e){
			\Uoowd\Logger::addWarning('Errore di autenticazione Provider: ' . $provider);
		}
	}


	/**
	 * set config and create hybrid instance
	 * @param string $redirect (default null) eventuale url di redirect dopo aver ottenuto i token dal provider
	 */
	private function setConfig($provider){
		if(is_null($redirect)){
			// error_log('redirect nullo');
			$host = $_SERVER['HTTP_HOST'];
			$redirect = elgg_get_site_url().'foowd_utenti/indexauth';
			if($provider === 'Google') $redirect = \Uoowd\Param::pageDNS()->indexauth;
			array_push($this->base_urls, $redirect);
		}
		// \Uoowd\Logger::addError('redirect : '.$redirect);
				
		// configurazione di bybridAuth
		$this->config = array(
			"base_url" => $redirect,
		   	"providers" => array (
		    "Google" => array (
		    	         "enabled" => true,
		        	      // "keys"    => array ( "id" => "108856046715-v5vl192ibtbit586p0klsp5oh0pl2elk.apps.googleusercontent.com", "secret" => "G95n2a3_dQHHXMNzgLZfvg71" ),
		    	         "keys"    => array ( "id" => elgg_get_plugin_setting('Google-Id', \Uoowd\Param::uid() ), "secret" => elgg_get_plugin_setting('Google-Secret', \Uoowd\Param::uid() ) ),
		            	  "scope"           => "https://www.googleapis.com/auth/userinfo.profile " . // optional
		                                   "https://www.googleapis.com/auth/userinfo.email "   . // optional
		                                   "https://www.googleapis.com/auth/plus.login "   . // NECESSARIO per avere la lista delle amicizie, ovvero le cerchie
		                                   "https://www.googleapis.com/auth/plus.me "   , // NECESSARIO per avere la lista delle amicizie, ovvero le cerchie

		              	// "access_type"     => "offline",   // optional
		             	// "approval_prompt" => "force",     // optional
		             	// "hd"              => "domain.com" // optional
		        ),
		    "Facebook" => array (
		              "enabled" => true,
		              // "keys"    => array ( "id" => "959554617440829", "secret" => "50a85f28e5edf60f51e371480cbe86b8" ),
		              "keys"    => array ( "id" => elgg_get_plugin_setting('Facebook-Id', \Uoowd\Param::uid() ), "secret" => elgg_get_plugin_setting('Facebook-Secret', \Uoowd\Param::uid() ) ),
		              
		              // https://developers.facebook.com/docs/facebook-login/permissions
		              "scope"   => "email " . // optional
		              				"user_friends user_relationships user_relationship_details "
		              // "display" => "popup" // optional
		    )
		   )
		 ); //config

		$this->hybridauth = new \Hybrid_Auth( $this->config );

	}// setConfig

}


/***** Links gestione app e account

Facebook:
https://developers.facebook.com/apps/959554617440829/settings/basic/
https://www.facebook.com/settings?tab=applications

Google+
https://security.google.com/settings/security/permissions?pli=1
https://console.developers.google.com/project/linen-source-92415


****/
