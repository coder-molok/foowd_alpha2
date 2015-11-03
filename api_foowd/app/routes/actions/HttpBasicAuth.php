<?php

namespace Foowd;



// piccolo check dell'origine: in teoria solo il sito remoto, ovvero elgg, e questo locale condividono una chiave privata
// Chiave privata: KFOOWD 

// In sostanza nell'header devo ricevere un tempo (F-Time) e una codice Hash (F-Check), quest'ultimo costruito in base a F-Time:
// se la sorgente e l'attuale servizio API utilizzano lo stesso metodo di generazione del codice hash (ivi inclusa la chiave "privata"), 
// allora e' possibile considerare attendibile la fonte.
// 
// Nella generazione del codice hash si usa il tempo attuale per fare in modo che ogni volta il codice hash cambi.

/**
 * HttpBasicAuth
 *
 * class middleware to check security.
 *
 * @see http://www.9bitstudios.com/2013/06/basic-http-authentication-with-the-slim-php-framework-rest-api/
 */


class HttpBasicAuth extends \Slim\Middleware {

    /**
     * @var string
     */
    protected $prkey;
 
    /**
     * Constructor
     *
     * @param   string  $realm      The HTTP Authentication realm
     */
    public function __construct($PKey = 'KFOOWD'){
        $this->prkey = $PKey;
    }

    /**
     * Controlla autenticazione curando:
     * - A replay attack (also known as playback attack)
     */
 
    public function authenticate() {
        $errors['check'] = false;
        $headers = $this->app->request->headers->all();
        //var_dump($this->app->request->isPost());
        //var_dump($headers['F-Check']);
        if( isset($headers['F-Check']) ){
   
            // ora corrente
            $date_utc = new \DateTime(null, new \DateTimeZone("UTC"));
            
            // ora di invio
            $received = $headers['F-Time'];
            $received = new \DateTime("@$received");
        
            // differenza in secondi:
            //var_dump($date_utc->diff($received));
            // se passano piu' di cinque minuti da quando la richiesta e' stata emessa, a quando mi giunge, la giudico inattendibile
            // NB: 5 minuti sono troppi, lo sto usando solo di esempio
            // NB2: filtrare successive chiamate tramite elgg per garantire rapidita' nell'invio, essendo le chiamate in localhost
            $elapsed = $date_utc->format('U') - $received->format('U');
            if($elapsed < 0 || $elapsed>360){
                $errors['elapsed'] = "E' passato troppo tempo dall'ultima richiesta: piu' di 5 minuti";
            }
        
            // echo " Received: ".$received->format('U');
            // echo " Now: ".$date_utc->format('U');
            // echo " Seconds: ".($date_utc->format('U')-$received->format('U'));
        
            $localHash = hash_hmac('sha256', $received->format('U'), $this->prkey);
            //var_dump($localHash);
            //var_dump( $headers['F-Check']);
            if ($localHash != $headers['F-Check']){ 
                $errors['check'] = "origine non sicura";
            } 
        }else{
            $errors['check'] = "Devi inserire F-Check nell'header";
        }

        return $errors;

    }
 
    /**
     * Call
     *
     */
    public function call(){   

        // se il metodo e' post, allora faccio un piccolo controllo sull'origine
        if($this->app->request->isPost()){
            $r = $this->authenticate();
            if(!is_bool($r['check'])){
                echo json_encode(array('errors'=>$r, "response"=>false) );
                return;
            }
        }
        // nel caso di successo, continuo con le chiamate
        $this->next->call();
    }
}


// Letture di spunto per slim framework
// http://www.lornajane.net/posts/2013/oauth-middleware-for-slim
// http://www.sitepoint.com/best-practices-rest-api-scratch-introduction/
// http://alexbilbie.com/2013/02/securing-your-api-with-oauth-2/