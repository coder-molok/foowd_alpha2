ToDo
=====

API Offerta
-----------

1. Modifica Offerta, ancora da scegliere cosa poter modificare e sotto quali condizioni (ad esempio: modifica se non ci sono ancora preferenze, oppure se non si e' ancora raggiunto il 50% del quantitativo minimo richiesto);



Validazione
===========
A titolo di promemoria, per sfruttare al massimo l'ORM `propel`, ho deciso che tutte la validazioni standard verranno specificate nel file `schema.xml` e implementate in
````
data/myConstraints/Propel/Runtime/Validator/Constraits
````