<?php


$method = 'create';
//var_dump($ar[$method]);
method($method);

what('Creo delle offerte, se non sono gia\' presenti');

setField($ar[$method], 'Name' , 'Cassa Arance');
setField($ar[$method], 'Publisher' , 5);
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'Name' , 'Formaggi');
setField($ar[$method], 'Publisher' , rand(100,200));
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);


$method = 'search';
//var_dump($ar[$method]);
method($method);

what('Svolgo delle ricerche, magari anche ordinando');

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , 'unset');	
API::Write($ar[$method]->url, $ar[$method]->method);

// max e min con curl non funziona, ma se lo testo direttamente sul browser non crea problemi
setField($ar[$method], 'Publisher' , '{"max":50, "min":1}');	// cerco tra i publisher con id compreso tra 1 e 50
setField($ar[$method], 'order' , 'Publisher');	//problema con Id, probabilmente perche' in formato json
API::Write($ar[$method]->url, $ar[$method]->method);



$method = 'update';
//var_dump($ar[$method]);
method($method);

what('Svolgo delle modifiche');

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , '63');	
setField($ar[$method], 'Price' , "5,78");
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , '88');	
setField($ar[$method], 'Price' , "5,78");
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);



$method = 'setState';
//var_dump($ar[$method]);
method($method);

what('Provo a modificare lo STATO');

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , '63');	
setField($ar[$method], 'State' , "close");
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , '88');	
setField($ar[$method], 'State' , "close");
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , '88');	
setField($ar[$method], 'State' , "caso");
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);



$method = 'delete';
//var_dump($ar[$method]);
method($method);

what('Elimino');

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , 'unset');	
setField($ar[$method], 'State' , "unset");
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'Publisher' , 5);
setField($ar[$method], 'Id' , '88');	
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

