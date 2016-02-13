Foowd Utility
=============

modulo per utility di carattere generale.



Prima Installazione
===================

Il plugin utilizza `bower` come package manager relativamente agli script php frontEnd e `composer` per ottenere particolari librerie PHP. Risulta pertanto doveroso, alla prima installazione, eseguire i comandi

````
$ bower install
$ composer install
````

Fatto questo, nel pannello d'amministrazione di Elgg (`<site>/admin`), seguire il path

````
Configure > Settings > Utility Foowd
````

e inserire i dati opportuni.

### IMPORTANTISSIMO!!!

>la prima volta eseguire un salvataggio nella pagina `Settings` sopra indicata, poiche' questa operazione genera dei files che vengono utilizzati dagli altri moduli `foowd_*`.

### Ultimo passo installazione

in ultimo abilitare il plugin

````
Web services 1.9
````

utilizzato per la generazione di API Interne.


### Test

all'interno della pagina `Utility Foowd` e' presente un link `Test` che reindirizza ad una pagina di test: questa risulta utile solamente per controllare che siano abilitati alcuni moduli e specifiche funzioni php generalmente non abilitate di default.

Avere tutti esiti positivi in questa pagina non implica che tutto filera' liscio, ma aiuta a capire se sono stati tralasciati grossi passi in fase d'installazione.


Dettagli
=========

In particolare:

1. `classes` contenente classi condivise con altri moduli. Il namespace di base e' `\Uoowd`.

2. `settings`, dento alla view: impostazioni configurabili direttamente dal pannello di amministrazione.

3. `API`, per visualizzare l'elenco di tutte le API disponibili andare al link 
    `<sito_elgg>/services/api/rest/json/?method=system.api.list`;
    I metodi interni dei plugin inizieranno con `foowd.***`. Inoltre nella stringa dell'url e' possibile sostituire `json` con `xml` per ottenere i dati restituiti dalla API in quel particolare formato.


Librerie
--------

### Bower

- `chosen` , javascript per inserire un tag dell'offerta nell'elenco dopo averlo cliccato

- `jqueryui-timepicker-addon` , plugin jquery-ui per poter estendere il calendario aggiungeno l'opzione per selezionare anche un orario

### Composer

- `monolog`, per scrivere i log di sistema (gia' utilizzato lato Foowd_API con SLIM framework)
- `PHPMailer`, per gestire la posta in fase di sviluppo. 

````
TODO: inserire bottone in settings per attivare/disattivare  l'invio di posta tramite PHPMailer
````

##### PHPMailer

Per quanto concerne la configurazione di PHPMailer con `gmail`, per rendere semplice l'autenticazione (ricordo che la casella viene utilizzata ad esclusivo impiego di test) e' necessario entrare nella propria casella email e:

- una volta autenticati, andare alla pagina [https://www.google.com/settings/security/lesssecureapps](https://www.google.com/settings/security/lesssecureapps) e attivare l'accesso ad App Meno Sicure

- eventualmente abilitare il protocollo IMAP

##### NB :
>Tutte le configurazioni per l'invio tramite `PHPMailer` vanno settate nei settings di `Foowd Utility`, raggiungibili dal pannello `Admin`.



