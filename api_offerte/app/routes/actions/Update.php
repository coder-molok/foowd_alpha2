<?php

// namespace associato in composer.json via psr-4
// in questo caso rappresenta il path a partire da actions

/**
 * classe per la creazione di nuovi record,
 * al momento testato solo per l'inserimento di una nuova offerta
 *
 * I contenuti vengono ottenuti in formato json e restituiti in tale formato.
 */
class Update{

	public $app=null;

	public function __construct($app){

		$this->app = $app;

		// in base al parametro call associo una specifica azione.
		// il parametro verra' impostato nei plugin Elgg.
		$this->data = json_decode($app->request()->getBody());//std class

		$this->{$this->data->call}();
	}

	/**
	 * per creare una nuova offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function offer(){
		// raccolgo i parametri della richiesta
		$data =  $this->data;
				$updates = array(
						'Name' => $data->name,
						'Price' => $data->price,
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
}
