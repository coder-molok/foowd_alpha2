ToDo
=====

API Offerta
-----------

1. Modifica Offerta, ancora da scegliere cosa poter modificare e sotto quali condizioni (ad esempio: modifica se non ci sono ancora preferenze, oppure se non si e' ancora raggiunto il 50% del quantitativo minimo richiesto);

2. Ricerca: allo stato attuale la ricerca viene fatta secondo un costrutto **AND**, ovvero ritorna solo i risultati che metchano **TUTTE** le condizioni.

3. `offerList` e `single`, ora possono essere rimpiazzate da `search`, cambiando semplicemente il parametro **type**.



Validazione
===========
A titolo di promemoria, per sfruttare al massimo l'ORM `propel`, ho deciso che tutte la validazioni standard verranno specificate nel file `schema.xml` e implementate in
````
data/myConstraints/Propel/Runtime/Validator/Constraits
````