<?php

$method = 'create';
//var_dump($ar[$method]);
method($method);


what('Creo 3 utenti, se non sono gia\' presenti');

setField($ar[$method], 'Name' , 'gigi');
setField($ar[$method], 'Genre' , 'standard');
setField($ar[$method], 'ExternalId' , 52);
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

//setField($ar[$method], 'ExternalId' , rand(100,200));
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

setField($ar[$method], 'Genre' , 'caso');
//setField($ar[$method], 'ExternalId' , rand(100,200));
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);





$method = 'delete';
//var_dump($ar[$method]);
method($method);


what('Provo a eliminare un utente a caso');

setField($ar[$method], 'ExternalId' , rand(100,200));
API::Write($ar[$method]->url, $ar[$method]->method, $ar[$method]->dataSet);

