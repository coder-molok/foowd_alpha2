# Installazione su Aruba

per installare su aruba, non potendo lanciare gli script in remoto, è necessario creare i file in locale utilizzando la configurazione
con le impostazione del server di produzione
	
	proper_prod.json

eseguire gli stessi step

	propel sql:build
	propel model:build

Per creare i config.hph

	propel config:convert 

uploadre tutto tramite FTP

