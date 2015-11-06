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
	public $base_urls = array();

	/**
	 * istanza classe HybridAuth
	 * @var [type]
	 */
	public $hybridauth;

	/**
	 * imposto le configurazioni e se necessario svolge l'autenticazione.
	 * 
	 */
	public function __construct(){
		// carico le classi
		
		\Uoowd\Logger::addError(__FILE__);
		
		$this->setConfig();

		if(isset($_GET['auth'])){
			$this->makeAuthentication($_GET['auth']);
		}

		// tramite url scelgo se Facebook o Google, almeno per il momento
		$providers = array('Google','Facebook');

		// provider a cui risulto gia' loggato
		// per gia' loggato si intende che l'utente ha gia' dato l'ok alla app
		$yet = $this->hybridauth->getConnectedProviders();

		if(!empty($yet)){
		 echo 'Providers collegati:';
			$str = '';
			foreach($yet as $y){
				$str .= "<li><a href=\"#\" class=\"soc-" . strtolower($y) . "\"></a></li>";
			}
			echo '<ul class="soc">' . $str . '</ul>';
		}

		// provider non ancora autorizzati
		$not_yet = array_diff($providers, $yet);
		if(!empty($not_yet)){ 
			echo 'Providers NON collegati:';
			?>
			<div style="font-size:10px; font-style: italic; color: silver;">
				Cliccando una delle icone sottostanti potrai collegare il tuo profilo food al relativo social network, cosi' potrai vedere quali dei tuoi amici utilizzano gia' questa piattaforma!
			</div>
			<?php
			$str = '';
			foreach($not_yet as $y){
				$str .= "<li><a class=\"soc-" . strtolower($y) . "\"href=\"?auth=$y\"></a></li>";
			}
			echo '<ul class="soc">' . $str . '</ul>';
		}

		echo '<div>Elenco delle amicizie: --> ancora da implementare <-- </div>';
		foreach ($yet as $pro) {
			echo "<h1>$pro</h1>";
			$this->getProviderData($pro);
		}

		// session_unset();

	}// end __construct()


	public function getProviderData($provider){


		try{
		    
			$adapter =  $this->hybridauth->getAdapter($provider);

			\Fprint::r($adapter->getUserContacts());

		    // svolgo l'autenticazione presso il provider:
		    // in questo step viene svolto il redirect alla pagina di login del provider,
		    // e nel caso sia gia' stato effettuato il login con conseguente accettazione, allora evita di svolgere il redirect ritornando direttamente l'adapter
		    // \Uoowd\Logger::addError('dopo hybrid');
		    // $adapter = $this->hybridauth->authenticate( $provider );

		    // se sono arrivato sino a qui, vuol dire che l'autenticazione e' andata bene,
		    // pertanto procedo col recuperare i dati tramite la APP e posso procedere con l'autenticazione lato Elgg		    
		    // \Uoowd\Logger::addError('dopo authenticate');
		    $user_profile = $adapter->getUserProfile();
		    $id = $user_profile->identifier;
		    // echo $id;
		    if( $provider === "Google" ){
		    	// echo $provider;
		    	$data = $adapter->api()->api("https://www.googleapis.com/plus/v1/people/$id/people/visible");
		    	\Fprint::r($data);
		    	if($data->error->message === "Insufficient Permission"){
		    		echo "riprovo";
		    		// $adapter->logout();
		    		// \Fprint::r($_SESSION);
		    		// $this->makeAuthentication($provider);

		    	}
		    }
		    // \Uoowd\Logger::addError($user_profile);
		    \Fprint::r($user_profile);

		}catch(\Exception $e){

			$adapter->logout();

			// \Fprint::r($e);
		  // su firefox devo forzare il logout: questo perche' se
		  // if(isset($adapter)){
		  //   \Uoowd\Logger::addError('ti slogghi');
		  //   $adapter->logout();
		  // } 
		  // // impongo il refresh della pagina dopo il logout
		  $page = '';//$_SERVER['PHP_SELF'];
		  $sec = "0";
		  header("Refresh: $sec; url=$page");
		}

	}// get provider data

	// attempting authentication
	public function makeAuthentication($provider){
		if($provider == "Facebook"){
			// error_log('Facebook');
			$url = elgg_get_site_url().'foowd_utenti/indexauth';
		}else{
			$url = 'http://http-foowd.ddns.net/foowd_utenti/indexauth';
		}

			$this->setConfig($url);
		

			try{
				$this->hybridauth->authenticate($provider);
			}catch(\Exception $e){
				echo "errore";
			}

	}


	/**
	 * set config and create hybrid instance
	 * @param string $redirect (default null) eventuale url di redirect dopo aver ottenuto i token dal provider
	 */
	public function setConfig($redirect = null){
		if(is_null($redirect)){
			// error_log('redirect nullo');
			$host = $_SERVER['HTTP_HOST'];
			$redirect = elgg_get_site_url().'foowd_utenti/indexauth';
			if($_GET['provider'] === 'Google') $redirect = \Uoowd\Param::pageDNS()->indexauth;
			// utilizzo l'ip impostato col servisio no-ip: necessario per testare l'app google
			// l'app di google richieste di passare il nome di un host, non un indirizzo ip!
			// if(filter_var($host , FILTER_VALIDATE_IP)){ 
			// 	// host temporaneo per test
			// 	$redirect = 'http://foowd.accaso.eu/elgg-1.10.4/foowd_utenti/indexauth';
			// 	array_push($this->base_urls, $redirect);
			// }
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
		            	  "scope"           => "https://www.googleapis.com/auth/userinfo.profile ". // optional
		                                   "https://www.googleapis.com/auth/userinfo.email"   , // optional
		                                   "https://www.googleapis.com/auth/plus.login"   , // NECESSARIO per avere la lista delle amicizie, ovvero le cerchie
		                                   "https://www.googleapis.com/auth/plus.me"   , // NECESSARIO per avere la lista delle amicizie, ovvero le cerchie

		              	// "access_type"     => "offline",   // optional
		             	// "approval_prompt" => "force",     // optional
		             	// "hd"              => "domain.com" // optional
		        ),
		    "Facebook" => array (
		              "enabled" => true,
		              // "keys"    => array ( "id" => "959554617440829", "secret" => "50a85f28e5edf60f51e371480cbe86b8" ),
		              "keys"    => array ( "id" => elgg_get_plugin_setting('Facebook-Id', \Uoowd\Param::uid() ), "secret" => elgg_get_plugin_setting('Facebook-Secret', \Uoowd\Param::uid() ) ),
		              
		              "scope"   => "email", // optional
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
