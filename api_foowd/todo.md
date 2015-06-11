ToDo
=====

API Offerta
-----------

1. Modifica Offerta, ancora da scegliere cosa poter modificare e sotto quali condizioni (ad esempio: modifica se non ci sono ancora preferenze, oppure se non si e' ancora raggiunto il 50% del quantitativo minimo richiesto);

2. Ricerca: allo stato attuale la ricerca viene fatta secondo un costrutto **AND**, ovvero ritorna solo i risultati che metchano **TUTTE** le condizioni.

3. `offerList` e `single`, ora possono essere rimpiazzate da `search`, cambiando semplicemente il parametro **type**.


API Utente
-----------

1. `delete` : decidere cosa fare

    - consentire eliminazione: e' possibile che un utente si cancelli mentre ha delle preferenze su offerte attive?
    - eliminazione: eliminare tutte le preferenze dell'utente? eliminarlo dai Gruppi a cui appartiene?


API Offerta
------------

1. `create` 
 
    - la quantita' `qt` deve essere un intero perche' le offerte vengono fatte "a pacchetti", o potrebbe anche essere un decimal ?
    - oltre alla data di creazione potrebbe essere utile inserire anche una data relativa all'ultima modifica? 


Validazione
===========
A titolo di promemoria, per sfruttare al massimo l'ORM `propel`, ho deciso che tutte la validazioni standard verranno specificate nel file `schema.xml` e implementate in
````
data/myConstraints/Propel/Runtime/Validator/Constraits
````