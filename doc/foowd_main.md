Foowd Main
==========

Questa pagina nasce allo scopo di ricordare gli strumenti principali sviluppati per la gestione del sito foowd.


Prima di proseguire ricordo altre pagine utili:

- [simone.md](simone.md) , per visionare i commit eseguiti da simone;
- [paths.md](paths.md) , in cui viene riassunta la struttura principale inizialmente adottata;
- [tutorial_sviluppo.md](tutorial_sviluppo.md) , utile agli sviluppatori per la preparazione dell'enseble lavorativa;




ELGG
=====

per quanto concerne la parte elgg vi sono varie sfumature da prendere in considerazione.



### Pannello Admin

ovvero la `Dashboard`. L'unico plugin che ha configurazioni impostabili dal pannello e' `foowd_utility`.

Per raggiungere tali impostazioni andare su `Configure-Settings > Utility Foowd`.

Nel pannello raggiunto e' possibile inserire:
- URL API;
- Elenco dei Tags;
- Impostare il livello di Debug (per sviluppatori);
- Impostre la visualizzazione del popup *"register_error()"* impostando la voce di Debug con la spunta;

##### Tags

Nel caso vengano cancellati per sbaglio, e' stata impostata una directory di backup all'interno di `/mod_elgg/foowd_utility/views/default/plugins/foowd_utility/`, dove vengono salvate in formato json le sette piu' recenti versioni dei tags (per giorno).

Qualora si voglia ripristinare una di queste, basta copiarla in `/mod_elgg/foowd_utility/views/default/plugins/foowd_utility/` e salvarla come `tags.json` in modo da sovrascrivere la precedente.



### Javascript

qualora si volesse utilizzare l'implementazione `AMD` con `Requirejs` , ho messo a disposizione i seguenti files:

- [utility.settings.amd.js](../mod_elgg/foowd_utility/js/utility.settings.amd.js) , che permette di utilizzare i settings impostati. 
    
    Definito in [start.php](../mod_elgg/foowd_utility/start.php) viene richiamato in require con stringra `utility-settings`

- [foowd.pages.amd.js](../mod_elgg/foowd_utility/js/foowd.pages.amd.js) , che permette raccoglie l'elenco delle pagine impostate nei plugins.
    
    Definito in [start.php](../mod_elgg/foowd_utility/start.php) viene richiamato in require con stringra `utility-settings`



### Php

gli script php di carattere generale sono impostati nel plugin `foowd_utility`, in particolare sottolineo la presenza delle seguenti **classi**:

- [Param.php](../mod_elgg/foowd_utility/classes/Uoowd/Param.php) , che racchiude tutti i parametri di carattere generale, condivisi anche con altri plugin della serie `foowd_*`





EXTRA
======


#### Visualizzazione Log

Agli amministratori e' permesso visualizzare l'elenco di tutti i log, in ordine cronologico inverso, andando alla pagina

- [http://www.foowd.eu/elgg/foowd_utility/log/](http://www.foowd.eu/elgg/foowd_utility/log/)


#### Salvataggio Immagini

Attualmente le immagini relative all'offeta vengono salvate in una directory sorella di `/elgg/`, secondo lo schema

````
/offerImg/User-{guid}/{offer-id}/
                                |-medium/img
                                |-small/img
                                |-immagine_originale
                                |-crop.json (serve per tenere i parametri di ritaglio)
````



#### Problemi nelle View e Javascript

Generalmente quando non si riesce a visualizzare una view, specialmente se appena create, l'errore e' attribuibile alla cache di Elgg.
Pertanto andare nella `Dashboard` e nel widget del `Control Panel` e cliccare 

    Flush the caches


