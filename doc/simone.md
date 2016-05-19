# Commit
elenco delle azioni principali associate ai commit svolti da Simone Scardoni.Alcuni dettagli sono visualizzabili in [foowd_main.md](foowd_main.md).



### 19/05/2016

- rimosso controllo sul campo "Site"



### 29/04/2016

- risolta issue #237


### 27/04/2016

- aggiunta Condizioni d'uso:
- - modificato API DB per registrazione data creazione utente
- - aggiunta approvazione condizioni d'uso al codice Javascript e PHP del form registrazione 

- risolta issue #234



### 23/03/2016

- rimozione "Minqt" e "Maxqt" dai forms e inibizione dei loro check sia lato Elgg che API DB

- modificate API DB, template e controller per allineamento funzionalita' temporanea



### 21/03/2016

- risolta issue relativa al posizionamento voci nel pannello utente


### 19/03/2016

- modificati form registrazione/modifica utente per nuovo parametro "MinOrderPrice" e impostazione relativi check sui propri moduli

- modificate API DB per inserimento "constraint" e risposta di Offer\search | Prefer\search per nuova impostazione check su TUTTI i prodotti dell'utente

- adattato Wall e ProductDetail per nuova metodologia (pertanto rispettivi templates e controllers)


### 18/03/2016

- creata branch "ordine-multiprodotto"

- creata tabella "offer_group_many" per gestione gruppi di offerte, e predispozione futura relazione molti a molti per opzione multi gruppi




### 09/03/2016

- inibita visualizzazione offerte scadute nel wall

- implementata codifica url per la query string



### 08/03/2016

- risolto problema compatibilita' pagine php/amd introducendo una conversione dinamica via ".htaccess"

- sistemato errore caricamento pagina modifica avatar



### 05/03/2016

- sistemate piccole issue



### 03/03/2016

- corretta ApiPrefer per aggiungere nome compagnia



### 01/03/2016

- eliminata tipologia di indirizzo, nazione e localita' da form di registrazione/modifica utente



### 27/02/2016

- preparata API per ottenere elenco regioni, province, comuni

- modificate tabelle API

- aggiornati i form registrazione/modifica utente per la suddivisione dell'indirizzo in campi specifici

- in foowd-service.js creata nuova funzione per rinvio delle chiamate get al servizio elgg expose

- modificato tema > lib > ... FoowdAPI.js per adeguare alcune funzioni



### 25/02/2016

- aggiornata Sicurezza API, con conseguente adeguamento di configurazioni.

- aggiornata wiki

- implementato servizio Expose delle api Elgg




### 24/02/2016

- risolta search

- risolta, almeno in parte, compatibilita' con IE e browser piu datati

- risolve varie issues




### 23/02/2016

- sistemata search amicizie

- dettaglio prodotto: inseriti messaggi d'errore e pointer "progress" alla prenotazione

- inseriti links e visualizzazione unita' di misura

- svolte alcune traduzioni e risolte issue



### 20/02/2016

- inibita rimozione pulsanti + e - nel passaggio a modalita' gruppo per board e dettagli prodotto

- aggiunti effetti per hide e slide in board, dopo purchase

- aggiunto adeguamento calcoli nello switch delle modalita e aggiornamento conteggio preferenze a purchase avvenuta



### 17/02/2016

- migliorata resa grafica caricamento form update offerta

- migliorato check alla digitazione

- implementata base per plugin jquery

- impostato mouse destro per prodotti wall




### 16/02/2015

- centrato wall orizzontalmente per IE e tablet

- risolta iss#205



### 15/02/2016

- ristruttorata gestione del wall inserendo caricamento a blocchi

- sulla stessa idea, gestione della ricerca a blocchi

- modificato pesantemente codice e logica di WallController.js

- aggiunta gestione locale dei dati caricati

- modificate ApiOffer per consentire match su tags o contenuti o titolo

- aggiunto plugin di supporto

- risolte varie issue

- personalizzato codice di AnimOnScroll.js plugin

- corretti alcuni check dei form

- aggiornati vari controller per modalita' gruppo



### 12/02/2016

- predisposto sistema di cache per il wall

- rimosse api offerte searchTmp per adeguarle al nuovo schema API

- implementazione search

- migliorato rendering del wall alla search

- risolte varie issue


### 10/02/2016

- introdotto nel wall lo switch di gruppo senza ricaricamento

- a ciascun post nel wall viene aggiunto un data-type ne contiene l'id

- risolte varie issue

- modifica permessi foowd_offerte/all

- sistemati vari stili css

- aggiornamento di Navbarsearch



### 09/02/2016

- lavoro sulla searchbar: predisposto terreno di lavoro per implementare tutte le skill richieste

- aggiornamento css e template handlebars per navbar

- introdotto modulo per gestione della simulazione del campo di ricerca come campo di input

- correzione bug



### 07/02/2016

- sostituito html per funzione di ricerca

- implementata funzione di ricerca con colorazione e underscore lampeggiante

- implementata colorazione dei tags nei post matchanti

- tentativo di nascondere i post non matchanti: allo stato attuale non funzia per via del design fixed



### 06/02/2016

- completamento iter modifica impostazioni utente e aggiunta di controlli di validazione

- iss#198



### 05/02/2016

- immesso campo Owner per iscritti offerenti

- corretti tutti controlli e vincoli sia in fase di registrazione che di update

- aggiunta opzione messaggio noscript per backend

- aggiunto nel backend caricamento del body con effetto fadeIn (per fini non solo estetici)

- aggiunto campo username a tabella utenti

- modificati form registrazione e settings utenti per consentire l'impiego e modifica di username e nome visualizzato



### 04/02/2016

- aggiunta opzione typologia utente: evaluating per il suo status. Aggiornare propel e autoload di composer.

- aggiunto stato di approvazione per nuovo utente produttore

- impostato accesso lettura/modifica campi profilo utente per gli amministratori

- aggiunti campi form

- aggiunto invio mail di notifica per utente che diventa "offerente"

- creato wrap al forward di uservalidationbyemail

- legati valori di name e username entity user elgg

- aggiunte pagine , views e hook per registrazione e validazione utenti



### 03/02/2016

- terminata pagina amministratori per tags, creata relativa action e modificate le API. Invio email agli amministratori

- risolta iss#135

- inserita possibilita' per amministratori di modificare le offerte, senza che ne consegua generazione di email di notifica

- risolto bug icone socials

- linearizzata gestione css in foowd_offerte e foowd_utenti



### 02/02/2016

- risolta issue#193

- aggiunta pagina notifica errore di registrazione

- wall: sostituita descrizione con elenco tags e aggiornamento helpers di handlebars. Vedi iss#135

- impostata modifica per suggerimento tags



### 01/02/2016

- iniziate correzioni relative a iss#191


### 31/01/2016

- rimossi controlli su campo file e descrizione dal form di registrazione e relativi check javascript



### 26/01/2016

- corretto bug aggiornamento iss#187 introducendo l'uso di deferred



### 25/01/2015

- corretto parametro modifica offerta

- modificati manualmente stile.css e a_foowd-icons.css per consentire visualizzazione immagine lente nel wall



### 23/01/2015

- inserito file in .gitignore



### 22/01/2016

- aggiornata pagina settings dei plugin

- aggiornato modulo settings autogenerato e utilizzato in js

- aggiornato foowdSync.sh per estrazione del thema custom di query.ui con l'opzione -f



### 21/01/2016

- risolto errore caricamento crop immagini dell'avatar

- inseriti colori in versione darken e lighten

- assestamento stili a nuovo schema colori



### 20/01/2016

- allineato backend con modifiche API.

- modifica API Prefer e Offer (entrambe su search), per fine di omogeneita' delle query.



### 19/01/2016

- inizio adattamento schema colori

- corretti piccoli errori negli avvisi

- introdotta funzione per fadeout messagi d'errore di systema (elgg.register_error('messaggio'))

- raffinamento visualizzazione iss#186


### 18/01/2016

- completato controllo completo modifica offerta e invio di relative mail

- introdotto controllo permessi d'amministrazione in cronjob

- risolta iss#186

- introdotto ordinamento nel salvataggio dei tags in Utility Foowd



### 17/01/2016

- modificato ApiPrefer per compatibilita' codice su VPS

- introdotto testo informativo nella pagina di modifica dell'offerta

- introdotto blocco javascript al submit del form in caso di offerta bloccata da preferenze in stato 'pending'

- completato iter relativo a modifica offerte con conseguente giro di mail: iss#77

- introdotto controllo relatvo a scadenza nel form di modifica dell'offerta




### 15/01/2016


- introdotte apiExpose per chiusura ordine

- aggiornato service.php

- completata pagina purchase per permettere agli amministratori di chiudere ordini specifici

- modificato foowdSync.sh e sulla VPS di sviluppo impostati permessi comandi `composer` e `bower` tramite `visudo`



### 14/01/2016

- iniziata modifica per update dell'offerta

- inserita pagina purchase.php dal pannello d'amministrazione per chiusura completa ordine da parte degli amministratori




### 13/01/2016

ho riassunto i lavori da dopo il 7/01/2016 in questo commit, in particolare

- rimosso giro delle 24h

- inserito controllo ApiPuchase 'create' relativo a `totalQt` e `Expiration`

- inserito controllo ApiPrefer 'create' relativo a `totalQt` e `Expiration`

- rimodellata risposta ApiOffer 'search' nell'elenco delle preferenze a solo quelle `newest`

- corretta PurchaseMessageEmail.php

- modificato datepicker del form di inserimento e modifica offerta per consentire l'eliminazione della data di scadenza



### 14/12/2015

- corretto bug di chiusura "troublesome" in ApiPurchase.php




### 21/11/2015

- modificata chiamata JS per action `foowd-purchase-leader`



### 10/11/2015

- sistemato bug in `ApiPrefer.php`



### 09/11/2015

- adattata action purchase per accettare lista di preferenze

- sistemata ApiPrefer per restituzione dati con ExternalId multipli




### 06/11/2015

- svolto prove OAuth e inseriti redirect per compatibilita' con Google provider

- visionato documentazione [phpdocumentor](http://www.phpdoc.org/) per php e [usejsdoc](http://usejsdoc.org/) per moduli javascript



#### 04/11/2015

- lavoro esterno: verificata ownership del redirect di google al link [https://console.developers.google.com/apis/credentials/domainverification](https://console.developers.google.com/apis/credentials/domainverification)



### 01/11/2015

- risolta iss#154




### 30/10/2015

- impostato obbligo inserimento quantita' massima (0 in caso non si voglia inserire un tetto)

- aggiunto handler a `Monolog` per `error_log`

- aggiunte traduzioni `italiano`

- risolte issues di testo

- risolto warning di `backtrace` in `Uoowd\Param`

- risolto warning `elgg_echo` nel form offerte

- aggiunto `Modernizr` ai path di `definejs`



### 29/10/2015

- aggiunto controllo su preferenze per il non superamento maxqt offerta

- inserito controllo nell'ordine: la `quantita' totale d'ordine`  non puo' superare la `Maxqt` dell'offerta

- assestate `ApiUser` per adattamento alla non univocita' della preferenza


### 28/10/2015

- completamento giro crontab con partenza dell'ordine dopo 24 h




### 27/10/2015

- creazione tabella `purchase` e tabella di join `purchase_prefer`

- implementazione API `purchase` con metodi `create` , `search` e `close`

- gestione ordine mediante classe `FoowdPurchase`

- implementato invio di email in caso di errore nella chiusura

- implementato crontab per controllo periodico su esistenza di ordini da chiudere





### 26/10/2015

- ampliato modulo `foowdServices` aggiungendo metodo POST `getFriendsOf()`

- scritta pagina wiki per riferimento 

- aggiornata tabella `prefer` e script che ne competono

- introdotta opzione per alert di `manutenzione`




### 25/10/2015

- implementate API Elgg Service  `foowd.picture.get` per ottenere le immagini senza esporre il path fisico delle stesse

- risolta iss#145 impedendo la visualizzazione di board altrui





### 24/10/2015

- bloccato accesso pagina `gallery` per gli utenti non `offerenti` : iss #157

- risolta iss #152

- abilitazione `cron jobs`

- aggiunta classe `FoowdCron`





### 23/10/2015

- disattivazione plugin 'advanced search'

- personalizzazione delle ricerche introducendo classe `\Uoowd\FoowdSearch`

- ricerche secondo iss#149

- attivare "invitefriends" se non ancora attivato

- introdotta classe `FoowdNeedleDependencies` che controlla la presenza di determinati plugin, e qualora non presenti visualizza un allert `SOLO AGLI AMMINISTRATORI`




### 21/10/2015

- migliorata pagina temporanea `my-preferences`

- creata classe `MessageEmail` per messaggi email in formato vsprintf, con HtmlBody e AltBody (per PHPMailer)

- inserito testo migliorato per messaggi html lato foowd




### 20/10/2015

- implementata logica pagina preferenze con esempio concreto

- scritta action per chiusura di un ordine




### 19/10/2015

- inserita possibilita' di configurazione di `PhpMailer` dai settings 

- impostati test per invio mail




### 18/10/2015

- ridotte opzioni di ricerca nella sidebar ai soli `user`

- sistemato check formato json nei settings di `foowd_utility`

- aggiunta pagina `test` richiamabile dai settings di `foowd_utility`

- aggiornato `Readme.md` di `foowd_utility`




### 16/10/2015

- pagina `my-preferences` per testare Api Foowd




### 12/10/2015

- aggiornamento alcuni readme dei plugin

- Attivazione plugin `web services 1.9` per servizio API

- Introduzione di `PhpMailer` come servizio SMTP di test

- Aggiunta scadenza alle offerte a al DB (`Expiration`)

- Aggiunto Plugin jquery.ui: `jqueryui-timepicker-addon`




### 11/10/2015

- Correzione Issues  #138 #139 #141




### 06/10/2015

- create **API**:
    + `group`, che partendo da una specifica offerta e una lista di id, ritorna la medesima offerta con le preferenze che matchano gli id passati
    + 'commonOffers' che dato un gruppo di id ritorna tutte le offerte che li riguardano, specificando per quascuna quali id l'hanno preferita

- ampliata descrizione nel [Tutorial_installazione.md](Tutorial_installazione.md)



### 05/10/2015

- estesa la visualizzazione della pagina `friends` grazie alla view `foowd_utenti/view/default/object/summary/extend`

- aggiunte funzionalita' a `foowd-service.js` e relativo `services.php`

- modificato `UserBoardController.js` per consentire la visualizzazione della board degli amici




### 04/10/2015

- creato `foowd-services.js`: modulo di servizio per richieste specifiche verso elgg( ad esempio check delle amicizie)

- impostato routing pagina amicizie: sovrascritta quella originale di elgg

- modificato script della board per renderla visualizzabile da altri utenti (amici)




### 03/10/2015

- migliorata interfaccia grafica sidebar

- sistemato problema caricamento dei settings

- personzalizzata pagina profilo di elgg

- traduzioni dei plugin




### 02/10/2015

- risolte iss #133, #136, #139

- attivato plugin Friends Request

- attivato plugin Search Advanced (necessita attivazione del plugin base Serach)

- sovrascritta la view del profilo

- sovrascritti alcuni menu utente




### 05/09/2015

- risolta iss#125

- aggiustati colori schermata di login/registrazione

- impostata proporzione 5:2 nella galleria utente

- risolto errata visualizzazione immagini caricate alla registrazione




### 02/08/2015

- completamento iss #70, #99,#107, #108, #111

- inserimento pagine di navigazione

- inserimento cookie policy e riabilitazione social logins 

- personalizzazione stili principali




### 27/07/2015

- adattata [issue#111](https://github.com/coder-molok/foowd_alpha2/issues/111)

- corretta [issue#109](https://github.com/coder-molok/foowd_alpha2/issues/108)

- corretta [issue#108](https://github.com/coder-molok/foowd_alpha2/issues/108)

- corretta [issue#104](https://github.com/coder-molok/foowd_alpha2/issues/104)

- svolta [issue#70](https://github.com/coder-molok/foowd_alpha2/issues/70)




### 22/07/2015

- in [start.php](../mod_elgg/foowd_utenti/start.php) impostata a *italiano* la lingua di default per i nuovi iscritti




### 20/07/2015

- Disattivazione Plugin:
    + Blog 1.9
    + Bookmarks 1.9
    + Log Browser 1.9
    + Log Rotate 1.9
    + File 1.9
    + Groups 1.9
    + Invite Friends 1.9
    + Likes 1.9
    + Members 1.9
    + Message Board 1.9
    + Messages 1.9
    + Pages 1.9
    + Search 1.9
    + Reported Content 1.9
    + The Wire 1.9
    + Zaudio
    + Notifications 1.9
    

- Plugin Attivi:
    + foowd_*
    + HTMLawed 1.9
    + Garbage Collector 1.9
    + Elgg Developer Tools 1.9
    + CKEditor 1.9
    + User Validation By Email 1.9
    + Profile 1.9


### 17/07/2015

- corretta [issue#88](https://github.com/coder-molok/foowd_alpha2/issues/88)


### 16/07/2015

- inserito script [foowdSync.sh](../script/foowdSync.sh)

- modificato header di risposta API

- modificato ritorno API offer



### 15/07/2015

- primi test su personalizzazione layout elgg

- migliorato crop delle offerte

- risolto errori ajax nel form di autenticazione



### 10/07/2015

- implementata API user per sincronizzazione nuovi dati utenti


### 07/07/2015

- implementata parte di [issue#85](https://github.com/coder-molok/foowd_alpha2/issues/85)


### 06/07/2015

- [issue#77](https://github.com/coder-molok/foowd_alpha2/issues/77) risultava gia' implementata



### 04/07/2015

- modificate api in accordo con [issue#75](https://github.com/coder-molok/foowd_alpha2/issues/75) 



### 25/06/2015

- implementate [issue#66](https://github.com/coder-molok/foowd_alpha2/issues/66) , [issue#68](https://github.com/coder-molok/foowd_alpha2/issues/68) e [issue#79](https://github.com/coder-molok/foowd_alpha2/issues/79)  aggiungendo i campi `@Unit@`, `@Quota@` e `@UnitExtra@` (quest'ultimo per dare un'ulteriore apporto all'unita') e sviluppata semplice preview formato quota

- risolta [issue#69](https://github.com/coder-molok/foowd_alpha2/issues/69) 
    
>NB: aggiornare DB e classi Propel mediante
>
>`propel diff`
>
>`propel migrate`
>
>`propel model:build`

- aggiornato [ApiUser.php](../api_foowd/app/routes/actions/FApi/ApiUser.php) e relativa [documentazione API](../doc/foowd_api/doc)


### 22/06/2015

- risolta [issue#61](https://github.com/coder-molok/foowd_alpha2/issues/61) 

- risolta [issue#67](https://github.com/coder-molok/foowd_alpha2/issues/67) 


### 18/05/2015

- sistemato crop per permettere diversi ratio immagine

- aggiornato salvataggio immagini a larghezza 400 per il Wall

- tolti gli spinner e rimessa la vecchia immissione testuale per i valori numerici

- aggiornata pagina [success.php](../mod_elgg/foowd_offerte/pages/foowd_offerte/success.php)



### 15/06/2015

- check del form offerte direttamente tramite js



### 13/06/2015

- aggiunto caso email gia' presente in [SocialLogin.php](../mod_elgg/foowd_utenti/classes/Foowd/SocialLogin.php)

- aggiunto inserimento Id e Secret della App Socials da pannello dei settings di utility foowd



### 11/06/2015

- risolta [issue#63](https://github.com/coder-molok/foowd_alpha2/issues/63) 

- risistemato grafica lista offerte in `<site_elgg>/foowd_offerte/all`

    > NB: eseguire il solito flush della cache!



### 10/06/2015

- sistemato file [composer.json](../api_foowd/app/composer.json)

- migliorato caricamento iniziale Crop Offerte



### 09/06/2015

- introdotto controllo case insensitive sull'estensione al caricamento dell'immagine relativa all'offerta



### 08/06/2015

- risolta [issue#60](https://github.com/coder-molok/foowd_alpha2/issues/60)

    >NB: andare in `mod_elgg/foowd_utility` e da li digitare
    > ````
    > $ bower install
    > ````

- Risistemato il medoto di immissione Tags in [settings.php](../mod_elgg/foowd_utility/views/default/plugins/foowd_utility/settings.php)

Puo' essere utile leggere [foowd_main.md#tags](foowd_main.md#tags) .


### 06/06/2015

- risolta [issue#44](https://github.com/coder-molok/foowd_alpha2/issues/44)

- risolta [issue#59](https://github.com/coder-molok/foowd_alpha2/issues/59)




### 05/06/2015

- risolta [issue#16](https://github.com/coder-molok/foowd_alpha2/issues/16): 
    
    >ATTENZIONE!!!
    > vedere [Readme.md](../mod_elgg/foowd_utenti/Readme.md#login-via-social) del plugin `foowd_utenti`.




### 03/06/2015

- inserito markdown [foowd_main.md](foowd_main.md).

- implementato sistema di gestione centralizzato pagine di navigazione via [foowd.pages.amd.js](../mod_elgg/foowd_utility/js/foowd.pages.amd.js).

- risolta [issue#55](https://github.com/coder-molok/foowd_alpha2/issues/55)
    
    >NB: in caso di problemi con la view ricordarsi di andare su `<site>/upgrade.php` oppure fare un `Flush the caches` dal pannello di Amministrazione.



### 02/06/2015

- inserito lightbox di upload immagine offerta.

- corretto bug creazione immagini sul server.

- impostazione log LEVEL da setting di **foowd_utility**: utile per gli sviluppatori.

- corretto bug relativo all'utente ADMIN introducendo al contempo il controllo sul `Genre` dell'utente.

- introdotta una semplice `search` per `ExternalId` nelle API `USER`, al fine di ovviare al bug di cui sopra.



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