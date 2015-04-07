Appunti 
========

sulla generazione delle classi Propel.

Di fatto:

- nella directory **data** sono presenti di file di configurazione di propel e dello schemaDB

- il file **composer.json** si trova allo stesso livello della directory appena generata

- mi piacerebbe usare il comando

    ````
    $ composer dump-autoload
    ````

    per fare in modo che sia composer stesso a generare l'autoload delle classi,  in che per caricare automaticamente tutte le classi dei pacchetti di composer e di propel sviluppato nella directory **data**  mi basti inserire all'indterno dello script php:

    ```` php
    require '../app/vendor/autoload.php';
    ````

#### Soluzione

in **composer.json** mi basta inserire il codice:

````
"autoload": {
    "classmap": ["./data/generated-classes/"]
}
````


### composer.json

Per fare in modo da rendere accessibili le classi di supporto che verranno create, sfrutto l'autoload di composer, aggiungendo alla voce *"autoload"*

````
"psr-4" : {
    "" : "routes/actions/"
}
````

il path e' relativo a alla directory del file composer.json


### Lavoro Propel

grazie al metodo sopra impiegato, e' possibile tenere la directory di lavoro pulita. A questo punto tutti i comandi propel per generare la query sql di partenza e la generazione delle classi devono essere eseguite all'interno della directory **/data/**.

Ricordo i comandi utili:
    - $ propel sql:build , per eseguire la query associata a **schema.xml**
    - $ propel model:build , per creare le classi
    - $ propel config:convert , per svolgere ulteriori ottimizzazioni

Importante comando da dare ogni volta che si creano o modificano classi:

    - $ composer dump-autoload , per ricreare l'autoload.

**ATTENZIONE:** dopo ogni *sql:insert* , propel DROPPA la tabella!!!

Per ovviare a questo, in seguito alla modifica di *schema.xml* posso dare il comando *$ propel sql:build* per fare generare la sql, poi modificare il file cosi' generato per evitare il DROP, e infine far eseguire questo file via *$ propel sql:insert*.

#### Extra su Propel

errori come **ModelCriteria::delete** possono essere dovuti a errori di impostazioni nello schema.xml, specialmente nella sezione delle foreign-keys.

Devo pertanto aggiornare lo schema.xml, e per evitare di DUMPARE tutti i dati salvati, posso volgere semplicemente i due seguenti comandi:

    - $ propel diff
    - $ propel migrate

Vedi http://propelorm.org/documentation/09-migrations.html


### MySql

comandi diretti da eseguire in mysql:

- accedere da terminale `mysql -u <user> -p`
- cambiare la tipologia di una colonna: ` use foowd_api; ALTER TABLE offer MODIFY COLUMN state ENUM('open','close');`

