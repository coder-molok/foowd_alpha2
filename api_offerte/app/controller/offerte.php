<?php

$app->get('/offers', function () {

	$offers = OfferQuery::create()
 		->orderByName()
  		->find();

	
    $offerArray = array();
    foreach($offers as $offer){
      array_push($offerArray, $offer->toArray());
    }
    echo json_encode($offerArray);

});



//Esempio API OFFERTE
//

//Crea offerta 
//Per ora 
$app->post('/offers', function () use ($app) {
    //Create book
	$offer = new Offer();
	$offer->setName($app->request->post('name'));
	$offer->setPrice(floatval($app->request->post('price')));
	$offer->setPublisher(0);
	$offer->save();

	echo "Creata ".$app->request->post('name');
});

