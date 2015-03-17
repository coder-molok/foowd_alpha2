<?php

class ApiOffer{


	public function __construct($app){

		$this->app = $app;

		// in base al parametro type associo una specifica azione.
		// il parametro verra' impostato nei plugin Elgg.
		$data = json_decode($app->request()->getBody());//std class
		$this->{$data->type}($data);
	}

	// equivalente a PUT
	public function create($data){

		//echo json_encode($data->body);
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
			$price = preg_replace('@,@', '.', $data->price);
			$offer->setPrice($price);
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
			$tgs = $single->getTagss();// doppia s!
			$ar['tags'] ='';
			foreach ($tgs as $value) {
				foreach(TagsQuery::create()->filterById($value->getId())->find() as $t){
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

	/**
	 * per aggiornare un' offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function update($data){
		// raccolgo i parametri della richiesta
		// al limite per il prezzo potrei creare un prefiltro in propel
		$price = preg_replace('@,@', '.', $data->price);
		$updates = array(
				'Name' => $data->name,
				'Price' => $price,
				'Description' => $data->description,
			);

		// recupero il post originale
		$offer = OfferQuery::create()
		  		->filterById($data->id)
		  		->filterByPublisher($data->publisher)
		  		->update($updates);

		//NB inserire controlli sui dati e risolvere problema sui tags		
		//$this->app->log->debug($offer);
		
		echo json_encode(array('response'=> true) );
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
			$tgs = $single->getTagss();// doppia s!
			$ar['tags'] ='';
			foreach ($tgs as $value) {
				foreach(TagsQuery::create()->filterById($value->getId())->find() as $t){
					$ar['tags'] .= $t->getName().', ';
				}
			}
			array_push($return, $ar);
		}
		echo json_encode(array('body'=>$return, 'response'=>true));
		
	}

}
