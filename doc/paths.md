
# Riassunto

lo scopo è quello di creare una struttura coerente per implementare delle API semi-REST, attualmente mirate all'interazione tra il plugin foowd_offerte di Elgg e il server API.

## API

allo stato attuale il `<path>` principale per svolgere le richieste è `<percorso personale>/public_html/api/`.

Poichè l'obbiettivo è principalmente quello di interagire con le entità Elgg che si creeranno durante la vita del sito, si è deciso di associare a ciascuna entità un'opportuna classe nel servizio API, all'interno della quale verranno svolte le operazioni API relative a quella specifica entità.

Nelle routes, il primo elemento dopo il `<path>` rappresenta proprio l'entità, ovvero l'oggetto da invocare, pertanto scrivere:

`<path>/offers`

rispecchierà l'azione di chiamare un'opportuna classe, in base a quanto deciso nel file `offerte.php`: in questo caso la **classe ApiOffer** presente dentro la cartella actions.

Le routes di Slim in questo caso lavorano solo sulle richieste GET e POST, in particolare:

- le richieste GET preleveranno i dati solamente dall'URL
- le richieste POST riceveranno i parametri internamente al body in formato json.

In entrambi i casi deve essere presente almeno la chiave **type** che rappresenta il metodo della classe. Ad esempio 

`<path>/offers?type=create&Id=12`

genererà la creazione di un'istanza della **classe ApiOffer**, che si occuperà di chiamare il **metodo create**. 
Tutti i parametri sono istanziati come membri della stdClass che ho sempre chiamato **$data**. Pertanto seguendo l'esempio 

````
echo $data->type; //produce "create"
echo $data->Id; // produce "12"
````

per rendere più agile la collaborazione con propel, tutti i dati inerenti le tabelle hanno come nome il **nomePhp** impostato in propel,
pertanto impostare 

`<path>/offers?Publisher=12` ,fa capire subito che tale dato servirà per svolgere qualche operazione relativa al **campo publisher** della tabella offer (il phpName nello *schema.xml* e' proprio Publisher, seguendo la convenzione CamelCase).


### Convenzioni sui Messaggi API

per avere un modello piuttosto coerente e che trasporti informazioni efficienti, ho pensato che mediamente i messaggi di ritorno dovrebbero essere in formato json, e contenere le seguenti specifiche:

#### Errori
la chiave sara' `errors`, e potra' a sua volta contenere le seguenti chiavi:

- *status* , che indica il codice del tipo di richiesta svolta (post 201, get 200)

- *msg*, lo specifico messaggio di ritorno

- *field*, piu' esattamente il nome del campo della tabella.

    questo errore sorge mentre si svolgono controlli sulla validita' del dato inserito, e il suo valore dovrebbe essere un messaggio d'avvertimento che potrebbe essere mostrato nel Form di elgg.

     Es:

    i tags dovrebbero essere singole parole, pertanto se nel form inserissi come tag "tanta roba", allora tenendo conto che nella tabella il campo e' "tag", si visualizzera' un errore simile al seguente
    ````
    {"errors":{"tag":"I tag possono solo essere singole parole separate da virgola"}}
    ````

#### Risposta

la chiave e' `response` e dovrebbe contenere solo **TRUE** o **FALSE**.

#### Body

la chiave `body` contiene un oggetto json, che rappresenta i dati utili ritornati alla richiesta esterna (Plugin Elgg nel nostro caso).


## Plugin Foowd_Offerte

tutte le richieste al servizio API vengono affidate alla classe `API`, in `classes/Foowd/`. Tale classe si basa sull'impiego di **Curl**, pertanto è necessario che il SERVER supporti tale modulo. Qualora cosi' non fosse, la classe e' stata progettata per essere facilmente riadattabile all'impiego di altri moduli (come ad esempio *HttpRequest*).

L'implementazione di `API` e' basilare, e attualmente in essa e' presente il solo metodo **Request**, da invocare secondo il comando

````
$r = \Foowd\API::Request($url, $method, $data);

// e utilizzare ad esempio

id($r->response){ ... #code ... }

````

dove troviamo:

- `$url` , parametro relativo al `<path>` e nel quale e' possibile aggiungere i parametri secondo la classica chiamata GET. In ogni caso, i parametri mediante URL verranno acquisiti dal server API solamente per le richieste di tipo GET.

- `$method` , GET o POST.

- `$data` , un array di dati da passare come corpo della richiesta. In ogni caso, i parametri passati in questo modo verranno acquisiti dal server API solamente per le richieste di tipo POST.

- `$r->response` , TRUE o FALSE a seconda del buon fine della chiamata.

Specifico che e' la classe stessa a svolgere le codifiche e decodifiche del formato json.

La classe ritorna tutti i dati forniti dal servizio API.


# Considerazioni


### Classe Action

dato che a mio avviso l'implementazione degli actions form in ELGG risulta relativamente dispersiva, ho creato la classe virtuale `Action` presente in `classes/Foowd`:

>questa classe ha lo scopo di raccogliere una serie di strumenti che verranno riutilizzati piu o meno in ogni form.

Inoltre le estensioni della classe conterranno una serie di parametri atti a svolgere validazioni e altre operazioni utili, associando tali parametri all'entita' specifica: vedi `FormAdd` che in maniera tortuosa fissa, relativamente all'entita' **offer**, tutti i dati necessari alla validazione del form, ritorno di errori e interazione al servizio API.


### NOTA PERSONALE

per rendere piu' sinergica e coerente la validazione dei dati sia lato plugin che lato API, potrebbe essere utile creare una classe statica contenente tutti i metodi di validazione che vorremo utilizzare.

Ad esempio i metodi `isCash(...)` e `isTag(...)`: definire tutte le validazioni in una sola classe da tenere sia nel server API che all'interno del plugin Elgg creerebbe un flusso di validazioni coerente.