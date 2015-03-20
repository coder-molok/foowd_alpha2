## Richieste
I tipi di richiesta sono solamente "POST" e "GET".

### Get

metodo utilizzato solo per reperire informazioni, pertanto associate ad operazioni di tipo find().

L'unico parametro necessario e' **type**, il cui valore rappresenta il metodo da richiamare. Ad esempio

    ````
    type=offerList
    ````
invochera' il metodo offerList() della classe selezionata in base alla route.

## Convenzioni sui Messaggi API

per avere un modello piuttosto coerente e che trasporti informazioni efficienti, ho pensato che mediamente i messaggi di ritorno dovrebbero essere in formato json, e contenere le seguenti specifiche:

#### Errori
la chiave sara' *errors*, e conterra' a sua volta le seguenti chiavi:

- *status* , che indica il codice del tipo di richiesta svolta (post 201, get 200)

- *msg*, lo specifico messaggio di ritorno

- *field*, piu' esattamente il nome del campo della tabella.

    questo errore sorge mentre si svolgono controlli sulla validita' del dato inserito, e il suo valore dovrebbe essere un messaggio d'avvertimento che potrebbe essere mostrato nel Form di elgg.

     Es:

    i tags dovrebbero essere singole parole, pertanto se nel form inserissi come tag "tanta roba", allora tenendo conto che nella tabella il campo e' "tag", si visualizzera' un errore simile al seguente
    ````
    {"errors":{"tag":"I tag possono solo essere singole parole separate da virgola"}}
    ````

