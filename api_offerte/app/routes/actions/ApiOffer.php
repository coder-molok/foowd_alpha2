<?php

// per convenzione tutte le richieste ritornano valori in formato json;
// ciascuno lo stato dell'operazione e' sotto la chiave (o attributo decodificato) response
// type specifica quale metodo richiamare


class ApiOffer{


	public function __construct($app, $request = null){

		$this->app = $app;

		// in base al parametro type associo una specifica azione.
		// il parametro verra' impostato nei plugin Elgg.
		
		switch($request){
			case null: 
				echo  json_encode(array('msg'=>'richiesta non specificata', 'response'=>false));
				return;

			case "post": // se il metodo e' post, allora i parametri vengono passati come body
				$data = json_decode($app->request()->getBody());//std class		
				break;

			case "get": // il metodo get acquisisce i parametri via url.
				$data = $app->request()->Params();
				break;
		}
		$app->log->warning($app->request()->Params());
		$app->log->warning($app->request()->getBody());

		if(isset($data->type)){
			$this->{$data->type}($data);
		}else{
			echo  json_encode(array('msg'=>'metodo non specificato', 'response'=>false));
		}
	}

	// equivalente a PUT
	public function create($data){

		$offer = new Offer();
		$this->offerManager($data, $offer);
		
	}

	/**
	 * per aggiornare un' offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function update($data){

		// recupero il post originale
		$offer = OfferQuery::create()
		  		->filterById($data->id)
		  		->filterByPublisher($data->publisher)
		  		->findOne();

		// cancello tutti i tag per riscrivere quelli aggiornati
		OfferTagQuery::create()
				->filterByOfferId($data->id)
				->delete();

		$this->offerManager($data, $offer);
	}

	/**
	 * per creare una nuova offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function offerList($data){

		//$data = $this->getData;

		$offer = OfferQuery::create()
				->filterByPublisher($data->publisher)
				->find();
		
		
		$return = array();
		
		//$ar['tags'] = $of->getTags();
		foreach ($offer as $single) {
			$ar['id']	= $single->getId();
			$ar['name']	= $single->getName();
			$ar['description']	= $single->getDescription();
			$ar['price']	= $single->getPrice();
			$tgs = $single->getTags();// doppia s!
			$ar['tags'] ='';
			foreach ($tgs as $value) {
				foreach(TagQuery::create()->filterById($value->getId())->find() as $t){
					$ar['tags'] .= $t->getName().', ';
				}
			}
			array_push($return, $ar);
		}
		echo json_encode(array('body'=>$return, 'response'=>true));
		
	}

	/**
	 * per eliminare una nuova offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function delete($data){

		$offer = OfferQuery::create()
		  	->filterById($data->id)
		  	->filterByPublisher($data->publisher)
		 	// ->delete();
		 	->find();

		 $status = false;

		 // in teoria la query dovrebbe restituire un solo valore, ma meglio controllare
		 if( $offer->count = 1){
		 	$offer->delete();
		 	$status = true;
		 }
		 
		//echo json_encode($count);
		echo json_encode(array(/*'body'=>$var,*/ 'response' => $status )  );	
	}


	protected function single($data){

		$offer = OfferQuery::create()
				->filterByPublisher($data->publisher)
				->filterById($data->id)
				->find();
		
		
		$return = array();
		
		//$ar['tags'] = $of->getTags();
		foreach ($offer as $single) {
			$ar['id']	= $single->getId();
			$ar['name']	= $single->getName();
			$ar['description']	= $single->getDescription();
			$ar['price'] = $single->getPrice();
			$tgs = $single->getTags();// doppia s!
			$ar['tags'] ='';
			foreach ($tgs as $value) {
				foreach(TagQuery::create()->filterById($value->getId())->find() as $t){
					$ar['tags'] .= $t->getName().', ';
				}
			}
			array_push($return, $ar);
		}
		echo json_encode(array('body'=>$return, 'response'=>true));
		
	}


	/**
	 * Di fatto creare una nuova offerta o aggiornarne una e' praticamente identico,
	 * pertanto tutte le azioni comuni (validazione e inserimento dei dati), 
	 * possono essere accumunati in questo metodo
	 * 
	 * @param  [type] $data  [description]
	 * @param  [type] $offer [description]
	 * @return [type]        [description]
	 */
	protected function offerManager($data, $offer){

			// memorizzo i singoli errori
			$errors = array();
			$proceed = true;

			// NB: da rivedere la strategia su come aggiungere e rimuovere i tags
			// 
			// prendo i tag inseriti dal form
			$actualTags = explode(',', $data->tags);
			$actualTags = array_unique($actualTags);	// evito eventuali ripetizioni
			$checkTags = true;
			$errors['tags'] = array();

			// valido i tag (posso anche impostarlo lato propel)
			foreach ($actualTags as $single) {
				// prima di salvare, controllo che il tag sia di una sola parola
				// da vedere: aggiungere controllo sulla presenza di caratteri speciali
				$single = trim($single);
				if(preg_match('@ +@i', $single)){
					array_push($errors['tags'], "errorone nel tag: ".$single);
					$checkTags = false;
					$proceed = false;
				}
			}

			// operazioni sul prezzo
			// i prezzi possono essere passati sia con la virgola che col punto
			// ci penso io a metterli nel formato giusto :-)
			if (preg_match('/^\d+(\,|\.)\d{2,2}$/', $data->price)){
				$price = preg_replace('@,@', '.', $data->price);
				$offer->setPrice($price);
			}else{
				$errors['price'] = "errore nel prezzo";
				$proceed=false;
			}


			// i settaggi li faccio solamente se tutti i controlli precedenti sono andati a buon fine 
			if($proceed){
				// NB: questo processo sarebbe meglio svolgerlo dentro al proceed TRUE
				unset($errors['tags']);	
				
				//$tagManager = OfferTagQuery::create()->find();
				//$tags = TagQuery::create()->find();
				foreach ($actualTags as $tag) {
					// salvo solo i tag nuovi, altrimenti la tabella dei TAG sarebbe piena di duplicati
		            $newTag = TagQuery::create()->filterByName($tag)->findOneOrCreate();
		            //$tags->append($newTag);
					$offer->addTag($newTag);
			    }
			}

			// prima di fare il presente settaggio dovrei controllare che l'id 
			// sia gia' associato ad un utente, altrimenti non avrebbe senso.
			// 
			// Provvedere una volta creata l'API per l'inserimento di un nuovo utente.
			// 
			$offer->setPublisher($data->publisher);
			$offer->setDescription($data->description);
			$offer->setName($data->name);

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

	
	// PARTE DI PROMEMORIA PERSONALE

	//NB: per utilizzare un processo piu' performante dovrei vedere le transiction di propel

	/****
	// memorizzo i singoli errori
	$errors = array();
	$proceed = true;

	// NB: da rivedere la strategia su come aggiungere e rimuovere i tags
	// 
	// prendo i tag inseriti dal form
	$actualTags = explode(',', $data->tags);
	$actualTags = array_unique($actualTags);	// evito eventuali ripetizioni
	$checkTags = true;
	$errors['tags'] = array();

	// valido i tag (posso anche impostarlo lato propel)
	foreach ($actualTags as $single) {
		// prima di salvare, controllo che il tag sia di una sola parola
		// da vedere: aggiungere controllo sulla presenza di caratteri speciali
		$single = trim($single);
		if(preg_match('@ +@i', $single)){
			array_push($errors['tags'], "errorone nel tag: ".$single);
			$checkTags = false;
			$proceed = false;
		}
	}

	// operazioni sul prezzo
	// i prezzi possono essere passati sia con la virgola che col punto
	// ci penso io a metterli nel formato giusto :-)
	if (preg_match('/^\d+(\,|\.)\d{2,2}$/', $data->price)){
		$price = preg_replace('@,@', '.', $data->price);
		$offer->setPrice($price);
	}else{
		$errors['price'] = "errore nel prezzo";
		$proceed=false;
	}


	// i settaggi li faccio solamente se tutti i controlli precedenti sono andati a buon fine 
	if($proceed){
		// NB: questo processo sarebbe meglio svolgerlo dentro al proceed TRUE
		unset($errors['tags']);	

		// voglio evitare che nella tabella dei tags vi siano duplicati, 
		// pertanto faccio prima a togliere tutti i tag dell'utente e inserisco quelli nuovi
		
		//$tagManager = OfferTagQuery::create()->find();
		$tags = TagQuery::create()->find();
		foreach ($actualTags as $tag) {
			// salvo solo i tag nuovi, altrimenti la tabella dei TAG sarebbe piena di duplicati
            $newTag = TagQuery::create()->filterByName($tag)->findOneOrCreate();
            $tags->append($newTag);
			$offer->addTag($newTag);
	    }
	}

	// prima di fare il presente settaggio dovrei controllare che l'id 
	// sia gia' associato ad un utente, altrimenti non avrebbe senso.
	// 
	// Provvedere una volta creata l'API per l'inserimento di un nuovo utente.
	// 
	$offer->setPublisher($data->publisher);
	$offer->setDescription($data->description);
	$offer->setName($data->name);

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
	****/