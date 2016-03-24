Offerta Singola
===============

procedimento per vincolare la realizzazione di una sola offerta per produttore; appuntato poiche' successivamente sara' rimossa.


Step
----

### Modifica ApiOffer.php search()

modifiche applicate intorno a riga: 


* 430, sull'oggetto query ritornato di default 
* 559, aggiunto `->filterById($oneOfPerPublisher)` per fare in modo di recuperare una sola offerta per Publisher, in questo caso quella che soddisfa la search.


### Modifica ApiOffer.php create()

all'inizio ho aggiunto un blocco per impedire la creazione qualora siano gia' presenti offerte di un produttore;


### modifiche lato elgg

A inizio pagina `form/foowd_offerte/add` per la creazione ho aggiunto un pre-controllo che inibisce la visualizzazione qualora siano gia' presenti offerte di un produttore.

#### wallController

- rimossa `query.offset` intorno a riga 208 
- rimossa `query.excludeId` intorno a riga 210 


#### userBoardController

* modificato metodo `_init()` per consentire un prefiltro delle offerte ammesse (vedi `parte per i gruppi`)
* linea 96, aggiunto loop per controllo Id ammessi grazie alla modifica di `_init()` (vedi `extra per modalita' singolo`)
* linea 145, aggiunti controlli su risposta `groups` e `actualProgress` (vedi `extra per moadlita' singolo`)


#### actions/foowd_offerte/add

riga 108, aggiunto un if() che si verifica solo in concomitanza con l'opzione `singleOfferError` nella funzione `create()` offer API 

