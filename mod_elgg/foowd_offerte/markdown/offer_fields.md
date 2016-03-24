Appunti Personali
=================

questo file non va considerato parte della documentazione.

Poiche' allo stato attuale il cambiamento di alcuni parametri comporta la modifica di varie pagine, ho deciso di appuntare i principali casi onde evitare di dimenticare un pezzo della catena qualora passi molto tempo dopo una modifica.


Minqt e Maxqt
-------------

sono presenti di defalut, ma in questo caso li voglio eliminare dal form pertanto: 

1 - eliminare dal form i relativi fields: views/default/forms/foowd_offerte/`<add.php|update.php>`

2 - eliminare i check su questi due dati da views/default/js/foowd_offerte/offer-form-check.amd

3 - rimuovere i parametri dall'array `$needle` di classes/Foowd/Action/FormAdd.php

4 - rimuovere i parametri da `$needle_create` e `$needle_update` di `ApiOffer.php`

5 - rimuovo la condizione `required` dallo `schema.xml` per questi campi