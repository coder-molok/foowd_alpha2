<?php

/**
 * Gestione dei Tags Suggeriti
 */
namespace Foowd;


class SuggestedTags {

	/**
	 * metadato associato a questo specifico oggetto: ne dovrebbe esistere uno;
	 * @var string
	 */
	private $subtype = 'foowdSuggestedTags';
	/**
	 * l'oggetto la cui descrizione contiene tutti i dati di interesse
	 * @var null
	 */
	public $river = null;
	/**
	 * ACCESS_LOGGED_IN
	 * @var integer
	 */
	public $river_access_id = 1;

	/**
	 * la prima azione e' il check e l'eventuale creazione: dovrebbe avvenire solamente una volta
	 */
	public function __construct(){

		$river = elgg_get_entities(
			array( 
				'type_subtype_pairs'=> array( 'object' => $this->subtype )
				)
		);
		$c = (is_array($river)) ? count($river) : 0 ;
		$ret = false;
		if($c >1){
			// dovrebbero vederlo solo gli amministratori
			echo 'Errore nel salvataggio dei tags.';
		}elseif($c == 1){
			// \Fprint::r($river[0]->getSubtype());
			$ret = $river[0];
		}else{
			\Fprint::r('salvo oggetto');
			// se non esiste lo creo: lo evito di cancellarlo e lo lascio sempre nel db
			$ret = new \ElggObject();
			$ret->subtype = $this->subtype;
			$ret->access_id = $this->river_access_id;
			$ret->save();
			
		}
		$this->river = $ret;
	}

	public function getDescription(){
		return (array) json_decode($this->river->description);
	}


	/**
	 * passo una lista (stringa) di tags e li salvo se non esistono;
	 *
	 * manda una mail all'utente che li ha aggiunti (senza fare check)
	 *
	 * manda una mail agli amministratori qualora il tag non esistesse gia'
	 * 
	 * @param [type] $pubId [description]
	 * @param [type] $ofId  [description]
	 * @param [type] $tgs   [description]
	 */
	public function setSuggested($pubId, $ofId, $tgs){

		if(!$tgs) return;
		
		// recupero i dati gia presenti
		$factory = $this->getDescription();
		
		// i tag formattati diventano le chiavi, e pudId e ofId diventano dei campi extra
		// preparo i dati
		$tgs = array_map('trim', explode(',', $tgs));
		$tmpTgs = array();
		foreach($tgs as $v){
			$v = preg_replace('@ +@i', '_', $v);
			if($v != '') $tmpTgs[] = $v;
		}

		// ricerca dei nuovi tag
		$newestTags = array();
		// se gia' esiste, evito di mandare altre email
		foreach ($tmpTgs as $v) {
			// ciascuna chiave e' un array di array
			// se non esisteva la creo e ritorno subito
			if(!isset($factory[$v])){
				$obj = new \StdClass();
				$obj->userId = $pubId;
				$obj->offerId = $ofId;
				$factory[$v][] = $obj;
				$newestTags[] = $v;
				continue;	
			} 
			// controllo se esiste la entry
			$exists = false;
			foreach($factory[$v] as $entry){
				if($entry->userId == $pubId && $entry->offerId == $ofId) $exists = true;
			}
			if(!$exists) $factory[$v][] = array('userId'=> $pubId, 'offerId' => $ofId);
		}
		// $this->river->description = '';
		$access = elgg_set_ignore_access(true);
		$this->river->description = json_encode($factory);
		$this->river->save();
		elgg_set_ignore_access($access);

		// mail all'offerente che suggerisce, sempre
		
		// mail agli amministratori se almeno un tag non esisteva
		if( count($newestTags) > 0 ){
			$this->adminMessage(implode(', ', $newestTags));
		}

		return $tmpTgs;
		
	}

	/**
	 * elimino il tag
	 * @param  [type] $obj [description]
	 * @return [type]      [description]
	 */
	public function delete($obj){
		// recupero i dati gia presenti
		$factory = $this->getDescription();

		$tmpF = array();
		foreach($factory as $key => $val){
			if(is_numeric($key)) continue;
			if( isset($obj->key) && in_array($key, $obj->key) ) continue;
			$tmpF[$key] = $factory[$key];
		}

		\Uoowd\Logger::addError($tmpF);
		$access = elgg_set_ignore_access(true);
		$this->river->description = json_encode($tmpF);
		$this->river->Save();
		elgg_set_ignore_access($access);
	}

	/**
	 * 
	 */
	protected function adminMessage($tags){
		$txt = "
		Sono stati proposti dei nouvi tags, nello specifico:\n

		%s
		
		Per la loro gestione puoi consultare l'apposita sezione del pannello utente.

		";

		$ar['body'] = sprintf($txt, $tags);
		$ar['subject'] = 'Proposta di nuovi Tag.';
		\Uoowd\Utility::mailToAdmins($ar);
	}



} // end class