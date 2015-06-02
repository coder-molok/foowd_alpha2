# Commit
elenco delle azioni principali associate ai commit svolti da Simone Scardoni.



### 02/06/2015

- inserito lightbox di upload immagine offerta.

- corretto bug creazione immagini sul server.

- impostazione log LEVEL da setting di **foowd_utility**: utile per gli sviluppatori.



### 01/06/2015

- risolta [issue#51](https://github.com/coder-molok/foowd_alpha2/issues/51):

    la soluzione si basa su quanto precedentemente svolto, ma in chiave **AMD**.

    > **IMPORTANTISSIMO**
    > 
    > per poter accedere alle configurazioni generali in chiave puramente javascript, e' necessario andare nei setting di **foowd_utility** e salvarli una volta: in questo modo viene generato in automatico un modulo AMD contenente i settings salvati (attualmente solo la url API).



### 30/05/2015

- corretto bug di commit issue#57: introduceva un errore nella creazione di una nuova offerta.

- inserita visualizzazione dei log: andando su `/foowd_utility/log` gli amministratori possono visualizzare l'elenco degli errori di Log generati dal sistema.

    > attualmente questa opzione risulta utile a me, ma in futuro il sistema di Log diventera' piu' specifico.


- risolta [issue#57](https://github.com/coder-molok/foowd_alpha2/issues/57):

    per rendere effettivi i cambiamenti nel DB e' necessario andare nella cartella contenente lo `schema.xml` di propel ed eseguire i seguenti comandi:

    ````
    $ propel diff         // per generare il file di migrazione nella cartella "generated-migrations"
    $ propel migrate      // per rendere effettivi i cambiamenti sul DB
    ````

    Purtroppo e' necessario svolgere un'ulteriore passaggio:

    - accedere a mysql da terminale
 
        ````
        $ mysql -u <utente> -p
        ````

    - aggiornare il campo **modified** della tabella **offer**
    
        ````
        > USE `foowd_api`; ALTER TABLE `offer` CHANGE `modified` `modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
        ````




### 29/05/2015

- risolta [issue#52](https://github.com/coder-molok/foowd_alpha2/issues/52):
    ora nei **settings** del pannello d'amministrazione, sezione **utility foowd** e' possibile inserire un elenco di **Tags** che verranno poi visulizzati nell'articolo. 

    >NB: i tags devono essere singoli nomi separati da virgola e non contenere lettere accentate

- per visualizzare i cambiamenti nel css aggiunto e' necessario aggiornare la cache di elgg andando all'indirizzo

    ````
    <sito_elgg>/upgrade.php
    ````



### 27/05/2015

- per visualizzare i cambiamenti e' necessario aggiornare la cache di elgg andando all'indirizzo

    ````
    <sito_elgg>/upgrade.php
    ````

- implementato salvataggio immagini con sistema di ritaglio:
    le immagini vengono salvate nella stessa directory del `<sito_elgg>` in un folder di nome `OfferImg`

    > NB: fare attenzione ai permessi di scrittora per creare il folder `OfferImg` :
    > lo script deve avere la possibilita' di creare immagini e directory in quest'ultima

- Nel file `foowd_utility/deactivate.php` ho impostato, in fare sperimentale, la possibilita' di mandare una mail a tutti gli amministratori alla disattivazione del plugin:
    
    ho pensato fosse utile, nel caso il sistema decida di disattivare il plugin in automatico *(quando la cache non e' attiva puo' capitare che non riesca a caricare le view, con conseguente disattivazione del plugin)*

    > per utilizzare questa opzione e' necessario impostare l'email del sito dal pannello di amministrazione ed avere abilitato php all'invio di email.

corretta [issue#50](https://github.com/coder-molok/foowd_alpha2/issues/50):
`propel diff` se si vuole vedere il cambiamento in `generated-migrations`
`propel migrate` per fare l'update del DB

### 20/05/2015

- corretta [issue#50](https://github.com/coder-molok/foowd_alpha2/issues/50): creato con successo utente *test* nel **DB API**
    
- corretta [issue#47](https://github.com/coder-molok/foowd_alpha2/issues/47)

- corretta [issue#46](https://github.com/coder-molok/foowd_alpha2/issues/46)



### 18/05/2015

- corretta [issue#40](https://github.com/coder-molok/foowd_alpha2/issues/40):
    ora l'unica API di ricerca e' `search`, che a ciascuna offerta aggiunge le chiavi:
    - *prefer*, ovvero la preferenza relativa all' **ExternalId** passato(se esistente), oppure null
    - *totalQt*, numero interno maggiore o uguale a zero
    - *Tag*, stringa contags o eventualmente di lunghezza nulla: ''
    

- implementato form offerte inserendo uno **SPINNER** HTML5 per i campi numerici *Price*, *Minqt* e *Maxqt*. 

    > NB: e' necessario caricare la view andando su `<sito elgg>/upgrade.php`



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