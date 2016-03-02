<?php

namespace Uoowd;

// NB pulire il database cercando tutte le entita' create oggi 16/01/2016, tra guid 136 e 142 probabilmente

class FoowdOffer{

	// imposto dei defult nel caso chiami senza il new

	// dopo quanto tempo invio la mail
	public $trigger = 3600	; // 1 h 
	// ogni quanto effettuo il cronTab
	public $cronTab = 1800 ; // 30 minuti

	public $checkEditMetatag = 'foowdOfferId';

	public $cronTimeRefer = 'time_created';

	public function __construct(){
		$this->trigger = 60*60;
		$this->cronTab = 30*60;
	}

	/**
	 * trovo la scadenza ESATTA
	 * @param  [type] $elggObj [description]
	 * @return [type]          [description]
	 */
	public function getExpiration($elggObj){
		$time = (new \DateTime())->setTimestamp( $elggObj->{$this->cronTimeRefer} );
		$deltaT = $this->trigger;
		$time->add(new \DateInterval('PT'.$deltaT.'S'));
		return $time; 
	}


	/**
	 * trovo la scadenza stimata, ovvero quella che viene visualizzata nel popup
	 * @param  [type] $elggObj [description]
	 * @return [type]          [description]
	 */
	public function getEstimateExpiration($elggObj){
		return \Uoowd\Utility::roundTimeTo($this->trigger,$this->cronTab, $elggObj->{$this->cronTimeRefer}); 
	}


	/**
	 * Controllo le offerte da concludere!
	 * @return [type] [description]
	 */
	public function solveEdited(){

		// \Uoowd\Logger::addError('Testo FoowdOffer');
		// Gli oggetti creati potrebbero avere accesso privato (solo per l'utente che li ha implicitamente creati)
		// pertanto per poter accedere alle entita' anche da questo script, che viene runnato senza permessi elgg (ne come user - ne come admin),
		// devo specificare di ignorare i permessi d'accesso.
		// In particolare i permessi d'accesso riguardano le query per ottenere le entita'. 
		// Una volta ottenute e  salvate in un array/variabile, sono ormai a disposizione (solo in lettura),
		// anche qualora successivamente si reimpostassero a standard i permessi d'accesso.
		// Peranto dopo aver ottenuto le entita' e' buona prassi ritornare alla normale modalita' di permessi.
		$access = elgg_set_ignore_access(true);
		// promemoria, ma non utilizzate
		// $access_status = access_get_show_hidden_status();
    	// access_show_hidden_entities(true);
		$elggOfr = elgg_get_entities_from_metadata(
			array( 'metadata_names'=>array( $this->checkEditMetatag ) )
		);
		// \Uoowd\Logger::addError(count($elggOfr));		
		// dopo la query e' buona prassi reimpostare i permessi d'accesso di default
		// promemoria, ma non utilizzata
		//  access_show_hidden_entities($access_status);
		// ritorno a ignore se devo accedere soltanto ai dati in lettura, 
		// 		ma non e' questo il caso, in quanto in caso di fallimento imposto lo stato dell'oggetto a failed
		// elgg_set_ignore_access($access);

		// foreach($elggOfr as $o){
		// 	\Uoowd\Logger::addError($o->description);
		// }



		if(count($elggOfr) <= 0 ) return;

				
		// attualizzo i valori
		foreach($elggOfr as $s){
			
			// nel caso debba mandare una mail o messaggi d'errore
			$this->actualElggObj = $s;

			$of = $s->description;

			// data di realizzazione
			$expiration = $s->{$this->cronTimeRefer};
			// se non ho ancora superato la scadenza allora non devo fare nulla
			if(new \DateTime() < $this->getExpiration($s)) continue;

			// raccolgo id offerta
			$ofId = $s->{$this->checkEditMetatag};

			if(!is_numeric($ofId)){
				// \Uoowd\Logger::addError('Errore metatag oggetto Elgg con GUID '. $s->guid .'. Si consiglia di controllare.');
				$s->delete();
				continue;
			}

			// risvolgo la chiamata per controllare che nel mentre non siano state espresse preferenze
			$r = $this->offerPrefersCall($ofId);
			

			if(!$r->response){
				// se lo stato e' failed, vuol dire che era gia' fallita in un controllo precedente,
				// pertanto gli amministratori 	hanno gia' ricevuto una mail... evito di rimandargliela!
				
				if($s->state === 'failed' ) continue;
				// imposto a failed, cosi' si realizzasolo questa volta
				$s->state = 'failed';
				// scrivo il log e avviso gli amministratori
				$this->adminMessage($this->request, $r);
				
				continue;
			}

			$body = $r->body;
			$bodyOf = $body[0]->offer;
			$pub = get_entity($bodyOf->Publisher);

			// se sono qui, tutto e' andato a buon fine
			// se sono qui, vuol dire che $body non e' vuoto
			$prefs = $this->prefersByState($body);
			// rendo disponibili gli array $newest e $pending
			extract($prefs);
			
			// \Fprint::r($newest);

			// se fa parte anche e solo di un ordine, allora non e' modificabile
			if(count($pending) > 0 ){

				// se lo stato e' failed, vuol dire che era gia' fallita in un controllo precedente,
				// pertanto gli amministratori 	hanno gia' ricevuto una mail... evito di rimandargliela!
				if($s->state === 'failed' ) continue;

				// imposto a failed, cosi' si realizzasolo questa volta
				$s->state = 'failed';
				// $s->save();		
				// mail all'offerente avvisandolo che non si puo' fare
				$txt = "
				Gentile %s,

        non è possibile modificare l'offerta \" %s \" perché è attualmente coinvolta in ordini pendenti.
        
        Cordialmente,
        Foowd

        -------------------------------------------------------------
        Informazioni necessarie per richiedere assistenza:
        Identificativo Sito: # %s
        Identificativo Offerta: # %s
        ";

				$body = sprintf($txt, $pub->username, $bodyOf->Name, $pub->guid, $ofId);
				elgg_send_email('Foowd Site', $pub->email, 'Modifica Offerta', $body, array() );

				// passo alla prossima offerta 
				continue;
			}

			// applico le modifiche: raccolgo i dati modificati
			$description = json_decode($s->description);

			// array da usare per le mail
			$email =  array();
			$email['ofName'] = $bodyOf->Name;
			$email['subject'] = 'Modifica offerta "' . $email['ofName'] .'"';
			$email['from'] = 'Foowd Site';
			$email['ofId'] = $bodyOf->Id;
			$email['listDiffs'] = array();

			// aggiorno l'offerta
			foreach($description as $key => $val){
				$v = $val->new;
				$bodyOf->{$key} = $v;
				$email['listDiffs'][] = array(
					'name' => elgg_echo('foowd:' . strtolower($key)),
					'old' => $val->old,
					'new' => $val->new
				);
			}

			// se tutto va a buon fine, proseguo con le API esterne
			$bodyOf->type='update';
			$bodyOf = (array) $bodyOf;
			// per la funzione adminMessage in caso di errore
			$this->request = $bodyOf;
			// chiamata per aggiornare!
			$r = \Uoowd\API::offerPost($bodyOf);

			if(!$r->response){
				// se lo stato e' failed, vuol dire che era gia' fallita in un controllo precedente,
				// pertanto gli amministratori 	hanno gia' ricevuto una mail... evito di rimandargliela!
				
				if($s->state === 'failed' ) continue;
				// imposto a failed, cosi' si realizzasolo questa volta
				$s->state = 'failed';
				// scrivo il log e avviso gli amministratori
				$this->adminMessage($this->request, $r);
				
				continue;
			}


			// e' andato tutto a buon fine, quindi posso eliminare
			$s->delete(true);


			// se sono qui, che vi siano preferenze o meno ormai e' modificabile
			foreach($newest as $p){
				error_log($p);
				$user = get_entity($p);
				// error_log('salve user: '.$user->guid);
				$email['usrName'] = $user->username;
				$email['who'] = 'usr';
				$email['emailTo'] = $user->email;
				$this->mailModified($email);

			}

			$email['who'] = 'pub';
			$email['usrName'] = $pub->username;
			$email['emailTo'] = $pub->email;
			// error_log('salve pub: ' . $pub->guid);	
			$this->mailModified($email);

			// qui mail a tutti
		
		}

	}


	/**
	 * data un'offerta, ritorna un array con chiave prefereza e valore array di id delle preferenze
	 * @param  [type] $body [description]
	 * @return [type]       [description]
	 */
	public function prefersByState($body){
		$prefs = array();
		foreach($body as $b){
			foreach($b->prefers as $p){
				if(!array_key_exists($p->State, $prefs)) $prefs[$p->State] = array();
				$prefs[$p->State][] = $p->UserId;
			}
		}
		return $prefs;
	}


	/**
	 * Confronto due offerte trovando le differenze
	 * @param  array $oldOffer [description]
	 * @param  array $newOffer [description]
	 * @return array           associativo contenente un sunto dei campi modificati inputDiffs e uno stato editableByDiffs
	 */
	public function findFieldDiffs( $oldOffer, $newOffer){
		// controllo le differenze tra i campi vecchi e quelli nuovi
		// solo i Tag, insieme alle Immagini, sono modificabili
		$editableByDiff = true;
		// con questo loop controllo la presenza di differenze
		$inputDiffs = array();
		foreach($newOffer as $key => $val){
			$old = $oldOffer[$key];
			$new = $newOffer[$key];
			// $new = (empty($new)) ? 'campo non definito' : $new;
			$changed = false;

			// la vecchia data la converto in una confrontabile con la nuova
			if($key == 'Expiration'){
				$old = date( 'Y-m-d H:i:s', strtotime($old) );
			}

			if($key === 'Tag'){
				$n = array_map('trim', explode(',', $new));
				$o = array_map('trim', explode(',', $old));
				if(array_diff($n, $o) === array_diff($o, $n)) continue;
				// error_log("new $new e old $old");
				$changed = true;
			}
			elseif($old != $new){

				// error_log(json_encode($oldOffer->{$key}));
				// error_log("vecchia: " . $old . " e nuova: ". $new);
				$changed = true;
			}

			if($changed){
				$inputDiffs[$key] = array();
				$inputDiffs[$key]['new'] = $new;
				$inputDiffs[$key]['old'] = $old;
				// se ho modificato qualcosa di diverso dai tag, allora blocco le modifiche
				if($key !== 'Tag') $editableByDiff = false;
			}
		}

		return array(
			'editableByDiff' => $editableByDiff,
			'inputDiffs' => $inputDiffs
		);

	}



	/**
	 * prima di chiudere devo controllare che nel mentre non siano state espresse preferenze!
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function offerPrefersCall($id){
		// L'unica modifica possibile e' quella dell'immagine
		// ora controllo se l'offerta e' modificabile o meno
		// raccolto i dati dell'offerta
		$prefCheck = array();
		$prefCheck['OfferId'] = $id;
		$prefCheck['type']='search';
		$prefCheck['State']='newest,pending';
		\Uoowd\Logger::addDebug($prefCheck);
		// lo salvo perche' lo posso usare nella mail di errori
		$this->request = $prefCheck;
		// // trasformo l'array associativo in una stringa da passare come URI
		$url=preg_replace('/^(.*)$/e', '"$1=". $prefCheck["$1"].""',array_flip($prefCheck));
		$url=implode('&' , $url);
		$r = \Uoowd\API::preferGet($url);
		return $r;
	}


	/**
	 * Lato admin e' utile su una pagina che mi permette di visualizzare il testo di questa funcion.
	 * Questa funzione rimane utile come promemoria per filtri elgg.
	 * 
	 * @return [type] [description]
	 */
	public function showFailedUpdate(){
		/*
		//
		// visualizzo tutte le entita' con checkEditMetatag, cosi' eventualmente trovo quelle in stato false
		$access = elgg_set_ignore_access(true);
		$elggOfr = elgg_get_entities_from_metadata(
			array( 'metadata_names'=>array( $this->checkEditMetatag ) )
		);
		
		// accedo solo in lettura
		elgg_set_ignore_access($access);
		// visualizzo tutti gli oggetti
		echo count($elggOfr);
		foreach($elggOfr as $s){
			// visualizzo solo quelli con stato false
			// if($s->state) continue;
			\Fprint::r($s);
		}
		*/


		// $usr = elgg_get_logged_in_user_entity();
		// $guid = $usr->guid;
		// $ent = elgg_get_entities(array('owner_guids'=>$guid));
		// foreach($ent as $e){
		// 	if($e->guid == $guid) continue;
		// 	// \Fprint::r($e);	
		// 	$metadata = elgg_get_metadata(array( 'metadata_owner_guid' => $user->guid, 'limit' => 0	));
		// 	foreach($metadata as $m){
		// 		// \Fprint::r($e);
		// 		if($m->owner_guid != $guid) continue;
		// 		echo 'owner: ' . $m->owner_guid . ' , meta guid: ' . $m->guid .' , nome: ' . $m->name . ' , valore: '. $m->value . " <br/>\n";
		// 	}
		// } 


	}


	/**
	 * messaggio da inviare agli amministratori
	 * @return [type] [description]
	 */
	protected function adminMessage($request, $response){
		$txt = "
		segnalo un problema di chiusura dell'ordine, in particolare i dati riguardandi sono: \n\n
		RICHIESTA API DB: \n %10s \n
		RISPOSTA API DB: \n %10s \n 

		L'entita' elgg associata a questi cambiamenti ha guid %s, e il suo state dovrebbe trovarsi in failes.
		";
		$request =  \Uoowd\Param::prettyJson(json_encode($request));
		$response =  \Uoowd\Param::prettyJson(json_encode($response));
		\Uoowd\Logger::addError('Errore richiesta API');
		\Uoowd\Logger::addError('richiesta: '.$request);
		\Uoowd\Logger::addError('responso: ' .$response);
		$ar['body'] = sprintf($txt, $request, $response, $this->actualElggObj->guid);
		$ar['subject'] = 'CronTab: errore aggiornamento offerta.';
		\Uoowd\Utility::mailToAdmins($ar);
	}


	/**
	 * messaggio mail che giunge a chi chiude l'ordine
	 * in caso di chiusura immediata dell'ordine.
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function mailModified($ar){
		// $ar = array();
		// $ar['ofName'] = 'formaggi DOP'
		// $ar['usrName'] = 'ginoRino';
		// $ar['listDiffs'] = array -> v
		// $ar['who'] = usr | pub
		
		extract($ar);

		if($who === 'usr'){
			$preamble = "Un'offerta su cui hai una (o più) preferenze è stata modificata.";
		}else{
			$preamble = "Una delle tue offerte è stata modificata!";
		}
		
		$msg = __CLASS__;
		$msg = new $msg();
		$dettaglio = array_map(array($msg, "fieldCouple"), $listDiffs);

		$managerMsgAlt = '
		Buongiorno %s,
		
		%s

		Segue il riepilogo complessivo:


		----------------------------------

		prodotto:          %s

		----------------------------------

		%s

		----------------------------------
		
		

		Saluti da foowd, e buoni acquisti!

		Siamo a tua disposizione per dubbi, problemi o feedback.

		';
		$managerMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $managerMsgAlt);
		
		$det = "";
		foreach ($dettaglio as $d) $det.=$d->altMsg;

		$alt = array( $usrName, $preamble, $ofName, $det);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';

		$managerMsgHtml = '
		<p>Buongiorno <b>%s</b>,</p>

		<p>%s</p>


		<p>Segue il riepilogo complessivo:

		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td><td>%s</td>
			</tr>
			<tr>
			<td>Campo</td><td>Vecchio<td>Nuovo</td>
			</tr>
			%s
			<tr>
			</tbody>
		</table>
		</p>

		<p><b>Siamo a tua disposizione per dubbi, problemi o feedback.</b></p>
		<p><em>Saluti da foowd, e buoni acquisti!</em></p>
		';

		$det = "";
		foreach ($dettaglio as $d) $det.=$d->htmlMsg;

		$html = array($usrName, $preamble, $ofUrl, $det);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($managerMsgHtml, $html);
		$tmp->altMsg = vsprintf($managerMsgAlt, $alt);
		// echo $tmp->htmlMsg;
		// echo '<pre>' . $tmp->htmlMsg . '</pre>';
		
		// invio le mail
		$ar['msg'] = $tmp;
		$this->sendMail( $ar );
	}

	/**
	 * i singoli ordini da elencare in managerOrderMsg e che possono qui essere raccolti come stringa
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function fieldCouple($ar){
		// $v = array();
		// $v['name'] = 12;
		// $v['new'] =  13.37;
		// $v['old'] = 'singolo';

		extract($ar);
		// prima tot e poi price!
		$new = (is_numeric($new)) ? number_format($new, 2, ',', ' ') : $new ;
		$old = (is_numeric($old)) ? number_format($old, 2, ',', ' ') : $old ;

		// in caso il campo sia vuoto
		$vacuum = 'Campo Vuoto';
		if( strcasecmp($name,"Expiration") == 0 ) $vacuum = 'Senza Scadenza';
		if($new == '') $new = $vacuum;
		if($old == '') $old = $vacuum;


		$strAlt = "    

			-> Campo: %s <- \n
			
			- vecchio valore: %s \n
			- nuovo   valore: %s \n";
		$alt = array($name, $old, $new);

		$strHtml = '
		<tr style="outline: thin solid silver;">
			<td style="text-align:left;">%s</td><td>%s</td><td>%s</td>
		</tr>
		';

		$html = array($name, $old, $new);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($strHtml, $html);
		$tmp->altMsg = vsprintf($strAlt, $alt);

		return $tmp;

	}

	/**
	 * invio una normale mail
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function sendMail($ar){
		// Mail al leader
		// il leader so gia' chi e' e sono gia' sicuro che sia un utente valido (vedi inizio script)
		// $leaderAr['from'] = $leader->username;
		// $leaderAr['mailTo'] = $offerName;
		// $leaderAr['subject'] = $publisher->username;
		// $leaderAr['mail'] = obj->altMsg e obj->htmlMsg;
		extract($ar);
		elgg_send_email($from, $emailTo, $subject, $msg->altMsg, array('htmlBody'=>$msg->htmlMsg) );
	}


}