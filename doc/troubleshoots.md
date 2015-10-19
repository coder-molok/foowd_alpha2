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

