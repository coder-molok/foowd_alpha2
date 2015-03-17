<?php

// namespace associato in composer.json via psr-4
// in questo caso rappresenta il path a partire da actions

/**
 * classe per la rimozione dei record,
 * al momento testato solo per la rimozione di un'offerta
 *
 * I contenuti vengono ottenuti in formato json e restituiti in tale formato.
 */

class Delete{

	public $app=null;

	public function __construct($app){

		$this->app = $app;

		// in base al parametro call associo una specifica azione.
		// il parametro verra' impostato nei plugin Elgg.
		$call = $app->request()->params('call'); // is string
		$this->getData = json_decode($this->app->request()->params('body'));
		$this->{$call}();
	}

	/**
	 * per creare una nuova offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function offer(){

		$data = $this->getData;
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


}
