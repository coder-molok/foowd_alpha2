<?php

// namespace associato in composer.json via psr-4
// in questo caso rappresenta il path a partire da actions

/**
 * classe per la creazione di nuovi record,
 * al momento testato solo per l'inserimento di una nuova offerta
 *
 * I contenuti vengono ottenuti in formato json e restituiti in tale formato.
 */

// NB: da rivedere il problema dei tag duplicati nella tabella dei Tags
class Put{

	public $app=null;

	public function __construct($app){

		$this->app = $app;

		// in base al parametro call associo una specifica azione.
		// il parametro verra' impostato nei plugin Elgg.
		$call = $app->request()->put('call');
		$this->{$call}();
	}

	/**
	 * per creare una nuova offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function offer(){

		$data = json_decode($this->app->request()->put('body'));
		//var_dump($data);

		// memorizzo i singoli errori
		$errors = array();
		$proceed = true;

		$offer = new Offer();
		//NB: per utilizzare un processo piu' performante dovrei vedere le transiction di propel

		// prima di fare il presente settaggio dovrei controllare che l'id 
		// sia gia' associato ad un utente, altrimenti non avrebbe senso.
		// 
		// Provvedere una volta creata l'API per l'inserimento di un nuovo utente.
		// 
		$offer->setPublisher($data->publisher);
		$offer->setDescription($data->description);
		$offer->setName($data->name);

		// NB: da rivedere la strategia su come aggiungere e rimuovere i tags
		// 
		// prendo i tag inseriti dal form
		$actualTags = explode(',', $data->tags);
		$checkTags = true;
		$errors['tags'] = array();

		// valido i tag (posso anche impostarlo lato propel)
		foreach ($actualTags as $single) {
			// prima di salvare, controllo che il tag sia di una sola parola
			$single = trim($single);
			if(preg_match('@ +@i', $single)){
				array_push($errors['tags'], "errorone nel tag: ".$single);
				$checkTags = false;
				$proceed = false;
			}
		}
		if($checkTags){
			// NB: questo processo sarebbe meglio svolgerlo dentro al proceed TRUE
			unset($errors['tags']);	

			// ora sviluppo la relazione many-to-many
			foreach($actualTags as $tgs){
				// dato che l'offerta viene creata una sola volta
				// non ho bisogno di controllare la presenza dei record nella tabella offer_tags
				$tag = new Tags();
				$tag->setName($tgs);
				$offer->addTags($tag);
			}
		}


		// operazioni sul prezzo
		if (preg_match('/^\d+\,\d{2,2}$/', $data->price)){
			$offer->setPrice($data->price);
		}else{
			$errors['price'] = "errore nel prezzo";
			$proceed=false;
		}

		// imposto le azioni e il ritorno

		if($proceed){
			$offer->save();
			//echo "salvato!\n";
			$return =json_encode(array('response'=>$proceed));
		}else{
			//echo "non puoi salvare";
			$return = json_encode(array('errors'=>$errors, 'response'=>$proceed));
		}

		echo $return;
	}


}
