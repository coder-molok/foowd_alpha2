<?php


$method = 'create';
//var_dump($ar[$method]);
method($method);

what('Creo delle offerte, se non sono gia\' presenti');

setField($ar[$method], 'OfferId' , 93);
setField($ar[$method], 'UserId' , 5);
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'OfferId' , 193);
setField($ar[$method], 'UserId' , 15);
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);



$method = 'search';
//var_dump($ar[$method]);
method($method);

what('Svolgo delle ricerche, magari anche ordinando');

setField($ar[$method], 'OfferId' , 93);
setField($ar[$method], 'UserId' , 'unset');	
API::Write($ar[$method]->url, $ar[$method]->method);

// max e min con curl non funziona, ma se lo testo direttamente sul browser non crea problemi
setField($ar[$method], 'OfferId' , 'unset');
setField($ar[$method], 'UserId' , '5');	
setField($ar[$method], 'offset' , 'unset');	
setField($ar[$method], 'order' , 'Created, desc');	
API::Write($ar[$method]->url, $ar[$method]->method);

setField($ar[$method], 'UserId' , '{"min":3, "max":200}');	
API::Write($ar[$method]->url, $ar[$method]->method);


$method = 'delete';
//var_dump($ar[$method]);
method($method);

what('Elimino Preferenza');

API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'OfferId' , 93);
setField($ar[$method], 'UserId' , '5');	
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);