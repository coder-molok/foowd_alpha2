<?php

// namespace associato in composer.json via psr-4
// in questo caso rappresenta il path a partire da actions

/**
 * classe per la creazione di nuovi record,
 * al momento testato solo per l'inserimento di una nuova offerta
 *
 * I contenuti vengono ottenuti in formato json e restituiti in tale formato.
 */
class Get{

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
				->filterByPublisher($data->publisher)
				->find();
		
		
		$return = array();
		
		//$ar['tags'] = $of->getTags();
		foreach ($offer as $single) {
			$ar['id']	= $single->getId();
			$ar['name']	= $single->getName();
			$ar['description']	= $single->getDescription();
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


	protected function single(){

		$data = $this->getData;

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
