<?php

/**
 * File classe SocialLogin
 */


//////////////// PROMEMORIA
/// dallo start.php:
/// prima prova ad accedere alla pagina "hauth", e se va a buon fine successivamente accede a "indexauth"

namespace Foowd;

require_once(elgg_get_plugins_path().\Uoowd\Param::pid().'/vendor/autoload.php');

//use \Hybrid_Exception as Hybrid_Exception;

\Uoowd\Logger::addError(__FILE__);

/**
 * Classe che interagisce con <b> HybridAuth </b> , in particolare svolge il login qualora l'utente non sia ancora connesso al sito foowd
 *
 * @todo implementare connessione account al login, e non semplice match della mail.
 */
class SocialLogin{

	/**
	 * Controllo il login dell'utente: se non ancora loggato provo anzitutto a controllare se posso loggarlo tramite un social.
	 *
	 * Viene implementato il file di configurazione per ogni Social Provider.
	 */
	
	public function __construct(){

		// se e' gia' loggato lo rimando indietro()
		// problema... questa la realizza anche se non sono loggato... mah!
		if( elgg_is_logged_in() ){
			// caso noscript redirect immediato
			// echo '<noscript>';
			// register_error('sei stato reindirizzato perche\'e risulti gia\' loggato');
		 	// forward(REFERER);
		 	// echo '</noscript>';

			// redirect
			// $text = 'Risulti gia\' loggato. A breve verrai indirizzato alla Home Page.';
			// $text .= '<script>window.setTimeout(function(){location.href = "'.elgg_get_site_url().'"}, 5000);</script>';
			// echo elgg_view_page('Redirect', $text);
			system_message('Sei gia\' connesso.');
			\Uoowd\Logger::addError('roba strana');
			// forward();
			// sleep(5);
		} 

		// NB uso questo stratagemma per far funzionare tutto da localhost su server pubblicato mediante noip
		// altrimenti elgg_get_site_url() sarebbe stata piu adatta
		// $authPage = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		// $authPage = dirname($authPage).'/indexauth';
		 

		$redirect = elgg_get_site_url().'foowd_utenti/indexauth';
		
		/****
		 * Per non avere problemi con google devo usare un redirect 
		 * 
		 * il semplice ausilio dei DNS funziona, ma il problema e' il login tramite elgg: 
		 * 
		 * questi e' sensibile agli url, nel senso che se mi loggo mentre sono in forward con get a DNS, 
		 * nel momento in cui eseguo il redirect a IP , l'utente non risulta loggato su HOST IP
		 *
		 * In sostanza il login funziona sull' HOST, ovvero sull'HOST dell'url: cambiarlo equivale, in parte, a cambiare sito.
		 * (potrebbe essere utile una lettura a multisite di elgg).
		 *
		 * Soluzione: devo inserire il DNS affinche' a google piaccia il reindirizzamento a un DNS, e per compatibilita' devo eseguire
		 * dei redirect in .htaccess di elgg, che da DNS puntino a IP:
		 *
		 *
		 *  RewriteEngine on
		 *  RewriteCond %{HTTP_HOST} ^foowd.accaso.eu
		 *  RewriteRule ^(.*)$ http://5.196.228.146/elgg1.10.4/$1 [QSA,L,R=302]
		 */

		// modifica fatta appositamente per google
		if($_GET['provider'] === 'Google') $redirect = \Uoowd\Param::pageDNS()->indexauth;
		// \Uoowd\Logger::addError($_GET);
		// \Uoowd\Logger::addError('redirect : '.$redirect);
				
		// configurazione di bybridAuth
		$config = array(
			"base_url" => $redirect,
		   	"providers" => array (
		    "Google" => array (
		    	         "enabled" => true,
		        	      // "keys"    => array ( "id" => "108856046715-v5vl192ibtbit586p0klsp5oh0pl2elk.apps.googleusercontent.com", "secret" => "G95n2a3_dQHHXMNzgLZfvg71" ),
		    	         "keys"    => array ( "id" => elgg_get_plugin_setting('Google-Id', \Uoowd\Param::uid() ), "secret" => elgg_get_plugin_setting('Google-Secret', \Uoowd\Param::uid() ) ),
		            	  "scope"           => "https://www.googleapis.com/auth/userinfo.profile ". // optional
		                                   "https://www.googleapis.com/auth/userinfo.email"   , // optional
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
		   ),
			"debug_mode" => true,
	    	"debug_file" => __DIR__."/bug.txt",
		 ); //config


		// tramite url scelgo se Facebook o Google, almeno per il momento
		$provider = $_GET['provider'];


		try{
		    
		    $hybridauth = new \Hybrid_Auth( $config );
		    
		    // svolgo l'autenticazione presso il provider
		    // \Uoowd\Logger::addError('dopo hybrid: tento autenticazione');
		    $adapter = $hybridauth->authenticate( $provider );
		    $this->adapter = $adapter;

		    // se sono arrivato sino a qui, vuol dire che l'autenticazione e' andata bene,
		    // pertanto procedo col recuperare i dati tramite la APP e posso procedere con l'autenticazione lato Elgg		    
		    // \Uoowd\Logger::addError('dopo authenticate');
		    $user_profile = $adapter->getUserProfile();
		    // \Uoowd\Logger::addError($user_profile);
		    $this->userProfile = $user_profile;
		    $this->idt = $provider.'-'.$user_profile->identifier;
		    $this->metadata = 'idAuth'.$provider;
		    
		    // controllo se e' gia' registrato mediante il suo identifier,
		    // e in tal caso gli svolto un login
		    $this->checkUser();

		    // eventuali altri codici
		    // exp of using the twitter social api: Returns settings for the authenticating user.
      		// $account_settings = $twitter->api()->get( 'account/settings.json' );
		    
		    // \Uoowd\Logger::addError('dopo getprofile');
		    // call Hybrid_Auth::getSessionData() to get stored data
		    // $hybridauth_session_data = $hybridauth->getSessionData();
		    // \uoowd\Logger::addError($hybridauth_session_data);

		}catch(\Hybrid_Exception $e){
			$this->catchException($e, $adapter);
		}catch(\Exception $e){
			$this->catchException($e, $adapter);
		}
	}


	/**
	 * cosa fare quando avviene un'eccezione, come ad esempio il fatto che l'utente abbia revocato i permessi dell'app
	 * @param  [type] $e       [description]
	 * @param  [type] $adapter [description]
	 * @return [type]          [description]
	 */
	public function catchException($e, $adapter){
		\Uoowd\Logger::addError('_Exception: ' . $e->getMessage() );

		// su firefox devo forzare il logout: questo perche' se
		if(isset($adapter)){
			// \Uoowd\Logger::addError('ti slogghi');
		    $adapter->logout();
		} 
		register_error('E\' avvenuto un errore di connessione al servizio social. <br/> Ci scusiamo per il disguido.');
		// impongo il refresh della pagina dopo il logout
		$page = elgg_get_site_url();
		$sec = "0";
		header("Refresh: $sec; url=$page");
	}


	/**
	 * Controllo se esiste gia' la sua mail, ed in tal caso salvo il suo IdAuth  e poi lo
	 *
	 * loggo,
	 * altrimenti controllo se esiste l'idAuth e in tal caso decido se:
	 * registrarlo	
	 *
	 * loggarlo qualora sia gia' presente (mediante il suo id)
	 * @return [type] [description]
	 */
	public function checkUser(){

		\Uoowd\Logger::addError('checkUser');
		
		$idt = $this->idt;
		$meta = $this->metadata;
		// var_dump($idt);
		
		// se la mail corrisponde gia' ad un utente, allora questo e' un array
		// $email = 'foowdtestamici2@outlook.it';
		$user = get_user_by_email($this->userProfile->emailVerified/* $email*/);
		$count = count($user);

		// nes caso l'utente sia unico, ovvero la sua mail, lo loggo, ma prima gli aggiungo i metadata del provider
		// negli altri casi invece cerco per metadata
		if($count == 1 ){
			$user[0]->{$meta} = $idt;
		}else{

			// restituisce sempre un array
			// empty se non trova nulla
			// NB: elgg rileva i metadata soltanto DOPO che l'utente ha confermato la registrazione via mail.
			$user = elgg_get_entities_from_metadata(
				// array('metadata_names'=>array('Genre'), 'metadata_values'=>array('standard'))
				// array('metadata_names'=>array('fake'), 'metadata_values'=>array('lol'))
				array( 'metadata_names'=>array($meta), 'metadata_values'=>array($idt) )
				);

			$count = count($user);

		}

		if($count == 0){
			// var_dump('registro');
			$this->registerUser();
		}

		// se array, allora ne ho tanti, pertanto meglio scrivere un log d'errore
		if($count > 1){
			// var_dump('troppi idAuth');
			\Uoowd\Logger::addError($idt.' : Questo metadata risulta presente in piu utenti ma dovrebbe essere univoco...');
		} 

		// se e' un oggetto, allora e' un utente registrato, pertanto eseguo io il suo login
		if($count == 1){
			// \Uoowd\Logger::addError('ora inizializzo utente');
			// var_dump('singolo utente da registrare');
			// loggo l'utente: vedere elgg reference: session
			// \Uoowd\Logger::addError(  $user[0]->email );
			try{
				// oppure non fare il login diretto, ma creare una api:
				// fare un redirect a una api (site_url + id utente magari) che a sua volta svolge un redirect alla pagina del pannello
				// infatti con questo gioco posso garantire la compatibilita' dell'host: esempio
				// header('Location: ' . elgg_get_site_url() . \Uoowd\Param::page()->elggAPI . 'api.list.all' , true, 302);
				// exit;
				login($user[0]  , true/* , $persistent = false */  );
				$user[0]->save();
				system_message('Login effettuato con successo!');
				// reindirizzo per via del successo della chiamata
				$fwd = elgg_get_site_url() . \Uoowd\Param::page()->panel;
				// $fwd = \Uoowd\Param::pageDNS()->panel;
				forward($fwd);
			}catch(\LoginException $e){
				\Uoowd\Logger::addError(  $e->getMessage() );
			}catch(\Exception $e){
				\Uoowd\Logger::addError(  $e->getMessage() );
			}
		}

	}


	/**
	 * carico i form di elgg aggiungendo un parametro, che a sua volta diventera'
	 * metadata dello user.
	 * @param  [type] $user [description]
	 * @return [type]       [description]
	 */
	public function registerUser(){
		// mostro il form di riempimento... precompilato
		// var_dump($user);
		$user = $this->userProfile;

		///////////// parametri presi dal social:
		
		// preparo le variabili per lo sticky form, nel caso non sia gia' impostato
		$form = 'register';
		elgg_make_sticky_form($form);
		$_SESSION['sticky_forms'][$form]['name'] = $user->firstName;
		$_SESSION['sticky_forms'][$form]['email'] = $user->emailVerified;

		// passo come extra l'identifier: serve per la view register/extend di foowd_utenti
		set_input('idAuth', $this->idt);

		// stampo il form per la conferma
		// la classe e' necessarie per rimodellare la larghezza
		
		$body = '<div class="elgg-form-account">' 
				. '<h4>Hai quasi completato l\'iscrizione, ti chiediamo soltanto di terminare la compilazione:</h4>'
				. elgg_view_form($form /*, array('class'=>"elgg-form-account")*/)
				. '</div>' ;

		$body = elgg_view_layout('walled_garden', array(
		   'content' => $body
		));

		echo elgg_view_page('Registrazione', $body);
		
	}


	/**
	 * codici errore di hybrid: quelli ritornati dall'exception
	 * 
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function hybridCode($code){
		switch( $code ){
		  case 0 : echo "Unspecified error."; break;
		  case 1 : echo "Hybriauth configuration error."; break;
		  case 2 : echo "Provider not properly configured."; break;
		  case 3 : echo "Unknown or disabled provider."; break;
		  case 4 : echo "Missing provider application credentials."; break;
		  case 5 : echo "Authentification failed. "
		              . "The user has canceled the authentication or the provider refused the connection.";
		           break;
		  case 6 : echo "User profile request failed. Most likely the user is not connected "
		              . "to the provider and he should authenticate again.";
		           $twitter->logout();
		           break;
		  case 7 : echo "User not connected to the provider.";
		           $twitter->logout();
		           break;
		  case 8 : echo "Provider does not support this feature."; break;
		}
	}

}