# Installazione ELGG


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