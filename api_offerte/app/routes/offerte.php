<?php
// // After instantiation
// $log = $app->getLog();
// $log->warning('Foo'); 
/*
 * API root
 * function without name and use: php closure
 */
$app->group('/api', function() use ($app){
	
	/*
	 * This group is for version the API
	 */
	$app->group('/v1', function() use ($app){

		/*
 		 * Basic CRUD for the offers
 		 *
 		 *
 		 * Ogni richiesta '/' richiama una specifica classe php presente in actions/.
 		 * Ciascuna di queste classi provvede a svolgere le operazioni opportune
 		 * 
		 */
		$app->group('/offers',function() use ($app){

			// all GET routes - Read
			$app->get('/', function() use ($app){
				//file_put_contents('test.txt', time());
				//$returned = new Delete($app);
				$returned = new Get($app);
			});

			// $app->get('/:id', function($id) use ($app){
			// 	echo $id;
			// });

			// all POST routes - Update
			$app->post('/', function() use ($app){
				//file_put_contents('test.txt', date());
				$returned = new Update($app);
			});

			// all PUT routes - Create
			$app->put('/', function() use ($app){
				$returned = new Put($app);
			});


			// all DELETE routes - Delete
			$app->delete('/', function() use ($app){
				$returned = new Delete($app);
			});

		});
	
	});

});











/*$app->get('/offers', function () {

	$offers = OfferQuery::create()
 		->orderByName()
  		->find();

	
    $offerArray = array();
    foreach($offers as $offer){
      array_push($offerArray, $offer->toArray());
    }
    echo json_encode($offerArray);

});*/



//Esempio API OFFERTE
//

//Crea offerta 
//Per ora 
/*$app->post('/offers', function () use ($app) {
    //Create book
	$offer = new Offer();
	$offer->setName($app->request->post('name'));
	$offer->setPrice(floatval($app->request->post('price')));
	$offer->setPublisher(0);
	$offer->save();

	echo "Creata ".$app->request->post('name');
});
*/
