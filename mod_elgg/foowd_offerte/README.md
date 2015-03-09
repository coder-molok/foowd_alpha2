# Plug in per visualizzazione e ricerca offerte da elgg.

- per il momento i files sono molto verbosi, ma a lavoro terminato sara' mia premura ripulirli
- rivedere i nomi ed i path, attualmente non ottimali



## Utilizzo

Per testare la **issue #12** dalla home di Elgg andare alla pagina **foowd_offerte/add**, dove si dovrebbe visualizzare il form.

Fatto questo compilare il form: l'unico controllo che svolge e' sulla voce **importo**, che dovrebbe contenere solo numeri con virgola e due cifre decimali.

Nel caso il campo **importo** non fosse corretto visualizza un messaggio di errore, altrimenti rimanda alla pagina **foowd_offerte/success** che visualizza solo una scritta di successo dell'operazione.

Da implementare:

- validazione,

    da rendere coerente alla validazione lato API

- action del form,

    se i dati sono corretti, creare un opportuno gruppo e salvare i dati in base all'implementazione API, scegliendo inoltre quali dati salvare anche nel ElggDB (vedere http://learn.elgg.org/en/latest/design/database.html#relationships )


## Suggerimenti

- visualizzazione success:

    visualizzare nella pagina di successo il posto completo relativo all'offerta

- Form di creazione dell'offerta:       

    eseguire un allert che chieda la conferma del modulo prima del salvataggio effettivo (naturalmente previo superamento della validazione)



# Specifiche



### activate.php e deactivate.php

in esse imposto e rimuovo delle variabili di configurazione di elgg, ad esempio l' URL per l'applicazione delle API.



### start.php

Qui dentro ho configurato i page_handler.



### classes/

Directory delle classi, in particolare per evitare conflitti ho impostato il un namespace in stile PSR.
