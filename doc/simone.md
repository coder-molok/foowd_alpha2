# Commit
elenco delle azioni principali associate ai commit svolti da Simone Scardoni.

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