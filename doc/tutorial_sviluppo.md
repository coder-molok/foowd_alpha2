# Tutorial Per sviluppatori

## Prerquisiti

* php 5.4
* git command line client  ([Windows Link](http://git-scm.com/download/win) )
* apache server
* mysql server

# Installazione Ambiente per sviluppo api_offerte 


## Clone dle repository 

Dalla tua  cartella di lavoro (una qualsiasi di vostra scelta d'ora in poi chiamo `<workspace>` ) copia  il progetto dal repository con 

	git clone https://github.com/coder-molok/foowd_alpha2


a questo punto avrai  nella cartella `foowd_alpha2` un clone del repository.


## Configurazione Apache

Per il funzionameno di Slim è necessario attivare il module Rewrite. 

Vedi [tutorial](http://www.webdevdoor.com/php/mod_rewrite-windows-apache-url-rewriting/)


## Installazione delle librerie php

per gestire le dipendenze ho usato [composer](https://getcomposer.org/).

Scaricare ed installare [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe) 


Entrare nella cartella `<workspace>/foowd_alpha2/api_offerte/` e lancuiare il comando 
`composer install`

Dovrebbe partire un programma di installazione che crea nella cartella una cartella `vendor/` con tutte le dipendenze.

## Installazione e Configurazione Propel

Per gestire le operazioni di scrittura e lettura dal database in PHP ho deciso di utilizzare l'ORM `Propel`.
Questo framework permette di scrivere velocemente tutto il codice PHP per relazionarsi con il database.

In particolare propone anche una serie di tool per creare automaticamente a partire da una descrizione delle tue tabelle 
sia l'sql per creare le tabelle nel DB sia il codice PHP corrispondente per maneggiarle.

Vedi [Tutorial](http://propelorm.org/documentation/02-buildtime.html)

Nella cartella versionata ho gia inserito i due file per la connessione e lo `schema.xml` che chiaramente andra aggiornandosi.

Per prima cosa è necessario quando si lavora poter richiamare lo scrip `propel` da qualsiasi directory quindi è comodo mettere nella variabile di ambiente  PATH

 `<workspace>/foowd_alpha2/api_offerte/vendor/propel/propel/bin/`

Questa cartella dovrebbe esistere se finito correttamente passo precedente. 


Poi è necessario creare su Mysql un database  `foowd_api` dando tutti i permessi a `foowd` con psw `mangioBENE`

	create database foowd_api
	GRANT ALL ON foowd_api TO 'foowd'@'localhost' IDENTIFIED BY 'mangioBENE'

A questo punto possiamo prima di tutto creare gli script `sql` lanciando da  `<workspace>/foowd_alpha2/api_offerte/`

	propel sql:build

questo dovrebbe creare una cartella `created-sql\ ` con all interno gli script per generare il database.
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



## Testare funzionamento 

A questo punto dovrebbe essere sufficiente copiare la cartella `api_offerte` nell'apache `htdocs`

Per verificare il funzionamento di SLIM andare al link 

	http://localhost/api_offerte/

Dovrebbe apparire una pagina di test con un pulsante per creare delle offerte random, e un link per vedere il json di tutte le offerte. 

## Ricapitolando

Al momento per il modulo api_offerte ho scelto di usare

* SLIM per la gestione delle chiamate REST
* Propel come ORM
* Composer per il build e il caricamento delle librerie


# Installazione Elgg


Seguire le indicazioni del tutorial [ufficiale](http://learn.elgg.org/en/latest/intro/install.html) creando prima il database vuoto per elgg in mysql



# installazione Ambiente per sviluppo Elgg


# Installazione Aptana

Per lo sviluppo sia della parte offerte sia della parte elgg consiglio [Aptana](https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=2&cad=rja&uact=8&ved=0CCYQjBAwAQ&url=http%3A%2F%2Fwww.aptana.com%2Fproducts%2Fstudio3%2Fdownload.html&ei=F2zRVLeFFM_gao7pgtAP&usg=AFQjCNFwqD4EHGmRf4gh1vER5GUE-aO4mg&sig2=sw2OZbG1KhhYA8Krrbck6A&bvm=bv.85142067,bs.1,d.bGQ)

## Installare Aptana
## Scaricare repository
## Settare project transfer
