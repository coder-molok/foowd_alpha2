# Tutorial Installazione 

I macro step per l'installazione sono 


* installazione API_foowd
* installazione elgg
* installazione e attivazione pluings

# Prerquisiti

* php 5.4       (modulo GD, libreria ext_curl)
* git command line client  ([Windows Link](http://git-scm.com/download/win) )
* apache server
* mysql server
* [node.js](https://nodejs.org/en/)
* [bower](http://bower.io/)




# API_foowd

## Clone del repository 

Dalla tua  cartella di lavoro (una qualsiasi di vostra scelta d'ora in poi chiamo `<workspace>` ) copia  il progetto dal repository con 

	git clone https://github.com/coder-molok/foowd_alpha2


a questo punto avrai  nella cartella `foowd_alpha2` un clone del repository.


## Configurazione Apache

Per il funzionameno di Slim è necessario attivare il module Rewrite. 

Vedi [tutorial](http://www.webdevdoor.com/php/mod_rewrite-windows-apache-url-rewriting/)

per il funzionamento di Elgg è necessario il modulo [php-gd](https://packages.debian.org/wheezy/php5-gd)
per il funzionamento delle API_Foowd dai plugin Elgg sono necessari il plugin [php-curl ](http://stackoverflow.com/questions/20073676/how-do-i-install-php-curl-on-linux-debian)


## Installazione delle librerie php

per gestire le dipendenze ho usato [composer](https://getcomposer.org/).

E' necessario installare un piccolo script queste le [info complete](https://getcomposer.org/doc/00-intro.md)

In breve su windows scaricare ed installare [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe) 


Entrare nella cartella `<workspace>/foowd_alpha2/api_offerte/app/` e lancuiare su linux il comando 

	 composer install



Dovrebbe partire un programma di installazione che crea nella cartella una cartella `vendor/` con tutte le dipendenze.

## Installazione e Configurazione Propel

Per gestire le operazioni di scrittura e lettura dal database in PHP ho deciso di utilizzare l'ORM `Propel`.
Questo framework permette di scrivere velocemente tutto il codice PHP per relazionarsi con il database.

In particolare propone anche una serie di tool per creare automaticamente a partire da una descrizione delle tue tabelle 
sia l'sql per creare le tabelle nel DB sia il codice PHP corrispondente per maneggiarle.

Vedi [Tutorial](http://propelorm.org/documentation/02-buildtime.html)

Nella cartella versionata ho gia inserito i due file per la connessione e lo `schema.xml` che chiaramente andra aggiornandosi.

Per prima cosa è necessario quando si lavora poter richiamare lo scrip `propel` da qualsiasi directory quindi è comodo mettere nella variabile di ambiente  PATH

 `<workspace>/foowd_alpha2/api_offerte/app/vendor/propel/propel/bin/`


Questa cartella dovrebbe esistere se finito correttamente passo precedente. 

spostartsi su `./data/`

Poi è necessario creare su Mysql un database  `foowd_api` dando tutti i permessi a `foowd` con psw `mangioBENE`

	create database foowd_api
	GRANT ALL ON foowd_api TO 'foowd'@'localhost' IDENTIFIED BY 'mangioBENE'

A questo punto possiamo prima di tutto creare gli script `sql` lanciando da  `<workspace>/foowd_alpha2/api_offerte/data/`

	propel sql:build

questo dovrebbe creare una cartella `generated-sql\ ` con all interno gli script per generare il database.
con

	propel sql:insert

se tutto è ok dovrebbero generarsi le tabelle.


Poi è possibile creare le classi vere e proprie.
	
	propel model:build

che crea la cartella `generated-classes`


Infine è necessare creare lo script di configurazione

	propel config:convert

che crea la cartella `generated-config`


Il file `index.php` richiama per funzionare il codice cosi generato.



## Registrazione autogenerate

Dopo aver generato le class con propel dovrebbe essere presente la cartella `<api-root>/data/genereted-classes`

A questo punto rilanciare 

		composer update
		
per registrare le nuove classi generate con propel con composer.

		


## Ricapitolando

Al momento per il modulo api_offerte ho scelto di usare

* SLIM per la gestione delle chiamate REST
* Propel come ORM
* Composer per il build e il caricamento delle librerie

per la connessione alle API da i plugin Elgg ricordarsi che è necessario installare 
per il funzionamento delle API_Foowd dai plugin Elgg sono necessari il plugin [php-curl ](http://stackoverflow.com/questions/20073676/how-do-i-install-php-curl-on-linux-debian)

## Verifica funzionamento API.

Puo essere utile testare le chiamate alle API senza passare da ELGG.
A questo scopo esistono diversi client REST quello piu usato è postman. Plugin applicazione Chrome [Postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop).

A questo indirizzo puoi trovare i file json da importare e preconfigurano le chiamate in postman.

https://github.com/coder-molok/foowd_alpha2/tree/dev/api_foowd/app/test/postman

chiaramente bisognerà cambiare gli indirizzi e i parametri delle chiamate, ma la struttura degli url è corretta.



# Installazione Elgg


Elgg puo essere installato dove vuoi, ovviamente a partire dalla  htdocs.
segui le istruzioni in [tutorial](http://learn.elgg.org/en/1.10/intro/install.html) 

per ora la 1.10 poi vedremo.

Le estensioni di elgg devono essere poi inserite in `<path installazione elgg>/mod` ad esempiop  `/var/www/htdocs/elgg/mod/` 
 e poi attivati dal pannello amministatore elgg. Vedi tutorial elgg su [plugin](http://learn.elgg.org/en/latest/admin/plugins.html#installation)


## Per mod_rewite

Assicurarsi che apache2 abbia montato il modulo mod_revrite (Le istruzioni sono per debian)

	a2enmod rewrite
	
permettere override. in debian `/etc/apache2/apache2.conf/`

	<Directory /var/www/>
		Options Indexes FollowSymLinks
		#Cambiato per elgg da	AllowOverride None
		AllowOverride All
		Require all granted
	</Directory>
	
verificare la presenza di .htacces in /var/www/html/elgg e che contenga la corretta linea `Rewrite /elgg/`

restartare apache2

# Plugins 


I plugin sono quattro, in ordine di attivazione

* foowd_utility (leggi *Prima Installazione* [Read.me](../mod_elgg/foowd_utility/Readme.md))
* foowd_offerte
* foowd_utenti
* foowd_theme

L'installazione consiste nel:

1. scaricare il codice del plugin. 
2. Generare il codice php con composer se necessario, 
3. generare il codice js con bower se necessario
4. copiare nella cartella `mod` di *Elgg* 


Il primo plugin da attivare e' `foowd_utility`, mentre per gli altri non e' importante l'ordine.

### ATTENZIONE!

Elgg carica i plugin in ordine di visualizzazione della `Dashboard` del pannello d'amministrazione, da quello piu alto a quello piu basso.

Poiche' i plugin `foowd_*` sovrascrivono le view e anche altre funzionalita' del `core` di elgg e' necessario **TENERLI TUTTI AL BOTTOM** della pagina dei plugin, seguendo l'ordine (dal piu basso):

1. foowd_utility (deve essere l'ultimo plugin dell'elenco)
2. foowd_theme
3. foowd_utenti
4. foowd_offerte

### Step Aggiuntivo

dopo aver attivato i plugin e' **IMPORTANTISSIMO** accedere alle opzioni di `foowd_utility`, pertanto dal pannello di controllo cercare e cliccare `Configure > Settings > Utility Foowd`.

Si accedera' cosi' ad una pagina nella quale e' possibile impostare
- url servizio API
- elenco dei tags e sottoclassi relativamente alle offerte
- parametri per il dialogo con le social api di `Facebook`, `Google+`, etc.

Infine ricordarsi di salvare

>NB:
	e' strettamente necessario svolgere un salvataggio la prima volta, poiche' tale azione permette di generare automaticamente il file `utility.settings.amd.js`, necessario per accedere alle configurazioni anche mediante moduli requirejs.

### Altri Plugin

Vanno inoltre installati i plugin:

 - [friend_request](https://elgg.org/plugins/384965)
 - [search advanced](https://elgg.org/plugins/2261455)

Dopo averli installati andare nella sezione `plugin` del *pannello d'amministrazione*, assicurarsi che gli appena citati plugin si trovino **SOPRA** i plugin `foowd_*` e attivarli.


### Elenco plugin da Attivare

.... lo scrivero' prossimamente ....



# Troubleshoots

e' possibile cercare la soluzione di alcuni problemi tipici alla pagina [troubleshoots](troubleshoots.md).



# Elenco Comandi

Qui di seguito elencare i comandi necessari per rendere operativi i commit, qualora ve ne sia bisogno.

1. Aggiornare Autoload di composer,

	dalla directory `foowd_alpha2/api_foowd/app` lanciare il comando

	````
	composer update
	````

2. Aggiornare Classi Propel,
	
	dalla directory `foowd_alpha2/api_foowd/app/data` lanciare il comando
	
	````
	propel model:build (se propel e' un comando riconosciuto)
	../vendor/bin/propel model:build (se propel non e' stato inserito nel path di sistema)
	````
	successivamente utilizzare il comando del punto **1** per aggiornare l'autoload delle classi tramite composer.

3. Refresh della cache di elgg,

	andare al link `<sito elgg>/upgrade`.

	Questo comando puo' tornare utile anche in fase di sviluppo qualora stranamente non appaiano delle view appena create.
	

Per rendere meno prolissi i messaggi del commit, consiglio di esplicitare i comandi da lanciare inserendoli dentro una parentesi quadra, ad esempio

*"[1] Aggiunta Classe per la gestione del gruppo"*

il numero **1** dentro la quadra indica che deve essere lanciato il comando 1 di questa lista, ovvero quello per l'aggiornamento dell'autoload di composer.


## Chiamate a API.

per il funzionamento delle API_Foowd dai plugin Elgg sono necessari il plugin [php-curl ](http://stackoverflow.com/questions/20073676/how-do-i-install-php-curl-on-linux-debian)



