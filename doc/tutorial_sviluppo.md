# Tutorial Per sviluppatori


# Installazione Ambiente per sviluppo api_offerte 

## Prerquisiti

* php 5.4
* git command line client  ([Windows Link](http://git-scm.com/download/win) )
* apache server


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

## Prova su apache 

A questo punto dovrebbe essere sufficiente copiare la cartella `api_offerte` nell'apache `htdocs`


# Installazione Elgg


# installazione Ambiente per sviluppo Elgg
