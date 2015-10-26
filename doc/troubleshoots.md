TroubleShoot
============

Raccolta di problemi e risoluzioni che possono avvenire.

Site
-----

#### errore 404 su <sito>/mod/foowd_utility/js/utility.settings.amd.js

quando si verifica questo errore e' perche' alla prima installazione e' necessario salvare almeno una volta i setting si **foowd_utility**. Per dettagli vedi [Tutorial installazione#step-aggiuntivo](Tutorial_installazione.md#step-aggiuntivo)


Propel
------

#### Can't connect to local MySQL server through socket '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock'

in genere e' dovuto ad un errore nel parametro `dsn` di `propel.json`. Dopo averlo modificato, potrebbe negare l'accesso all'utente `foowd`: in tal caso rivedere il [tutorial di sviluppo](Tutorial_installazione.md).


#### SQLSTATE[28000] [1045] Access denied for user 'foowd'@'localhost' (using password: YES)

problema di configurazione lato mysql: tipicamente non si sono impostati i corretti permessi. Provare con 
````
create database foowd_api
GRANT ALL ON foowd_api TO 'foowd'@'localhost' IDENTIFIED BY 'mangioBENE'
````
NB:

suggerisco di controllare che la password di `elgg` in `<elgg>/engine/config.php` sia la stessa di `propel.json` : in teoria possono essere differenti, ma allora i permessi andrebbero garantiti per entrambe le password, cosa che probabilmente crea inituili problemi.

La diversita' nelle password e' dovuta al fatto che la password sul server e' differente da quella nel repo, semplicemente per motivi di sicurezza.