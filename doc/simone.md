# Commit
elenco delle azioni principali associate ai commit svolti da Simone Scardoni.



### 18/05/2015

- corretta [issue#40](https://github.com/coder-molok/foowd_alpha2/issues/40):
    ora l'unica API di ricerca e' `search`, che a ciascuna offerta aggiunge le chiavi:
    - *prefer*, ovvero la preferenza relativa all' **ExternalId** passato(se esistente), oppure null
    - *totalQt*, numero interno maggiore o uguale a zero
    - *Tag*, stringa contags o eventualmente di lunghezza nulla: ''
    

- implementato form offerte inserendo uno **SPINNER** HTML5 per i campi numerici *Price*, *Minqt* e *Maxqt*. 

    > NB: e' necessario caricare la view andando su `<sito elgg>/upgrade`



### 13/05/2015

- corretta [issue#39](https://github.com/coder-molok/foowd_alpha2/issues/39)

- corretta [issue#38](https://github.com/coder-molok/foowd_alpha2/issues/38)

- corretta [issue#37](https://github.com/coder-molok/foowd_alpha2/issues/38)

    Tra i parametri ora si puo' passare anche "match" nella forma:

    ````
    match={"Name":"cassa di formaggi"}
    ````
    
    In questo modo nella colonna "Name" vengono filtrati tutti i titoli che contengono la parola 'cassa' o 'formaggi'. L'ho realizzata in questo modo per poter   eventualmente svolgere anche una ricerca sulla descrizione:
    
    ````
    match={"Description":"formaggi trentini"}
    ````

    in modo da cercare tutti i post la cui descrizione contiene la parola 'formaggi' o 'trentini'.


### 30/04/2015

- corretta [issue#12](https://github.com/coder-molok/foowd_alpha2/issues/12): vedere i commenti della issue stessa.

- corretta [issue#28](https://github.com/coder-molok/foowd_alpha2/issues/28).

- corretta [issue#32](https://github.com/coder-molok/foowd_alpha2/issues/32).

- corretta [issue#33](https://github.com/coder-molok/foowd_alpha2/issues/33).

- corretta [issue#34](https://github.com/coder-molok/foowd_alpha2/issues/34).

- corretta [issue#35](https://github.com/coder-molok/foowd_alpha2/issues/35).



In merito alla **Issue 34**:

creato il metodo `searchPrefer`, che richiede l' `ExternalId` come parametro obbligatorio. Qualora vi sia il match tra la singola preferenza e l'ExternalId, allora alla preferenza viene aggiunta la chiave "prefer", che diventa un oggetto json contenente i dati relativi alla preferenza.




### 26/04/2015

- corretta [issue#28](https://github.com/coder-molok/foowd_alpha2/issues/28) sostituendo la frase con *"L'id passato non e\' associato a nessun utente API"*

- corretta [issue#22](https://github.com/coder-molok/foowd_alpha2/issues/22).
     Da `foowd_utility` lanciare `composer install`.

     Per utilizzare il Logger basta inserire un codice del tipo
     ````
     \Uoowd\Logger::addWarning( string );
     ````
     dove ad add Warning possono semplicemente essere sostituiti i classi addDebug, addInfo, addError e gli add di **Monolog** in generale.

- inserita registrazione di un utente lato ADMIN

- inserito pulsante "Crea Nuova" lato **Pannello Utente**