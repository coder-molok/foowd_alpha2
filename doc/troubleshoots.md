TroubleShoot
============

Raccolta di problemi e risoluzioni che possono avvenire.

Propel
------

#### Can't connect to local MySQL server through socket '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock'

in genere e' dovuto ad un errore nel parametro `dsn` di `propel.json`. Dopo averlo modificato, potrebbe negare l'accesso all'utente `foowd`: in tal caso rivedere il [tutorial di sviluppo](Tutorial_installazione.md).

