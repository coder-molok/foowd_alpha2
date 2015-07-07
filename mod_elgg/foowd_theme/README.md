# Foowd Theme

Questo è un tema sviluupato con **Elgg** per la piattforma **Foowd**.
Aggiunge tutte le schermate personalizzate della piattaforma:

In particolare 

il wall con la lista delle offerte [vedi](http://localhost/elg/wall)
Il dettaglio dell'offerta
Il dettaglio delle preferenze inserite



## Installazione

Per attivare il plugin bisogna prima di tutto installare **Elgg** in un prorpio virtual server locale
> **Nota :** per esempio Apache.


Successivamente bisogna copiare la cartella **foowd_theme** in :

		<path/to/elgg>/mod/


Alternativamente si può creare un link simbolico con il comando:

	ln -s path/to/mod/folder/ path/to/elgg/mod/folder/
	
In questo modo si può modifcare il plugin senza tutte le volte aggiornare quello all'interno della cartella mods.

Sucessivamente bisogna spostarsi a seconda della procedura fatta precedentemente nella cartella della mod di elgg.
	
	cd <foowd_theme>
	chmod -R +x scripts

Installare alcuni moduli npm

	npm install -g bower stylus jeet rupture handlebars

Eseguire lo script di installazione
	
	./scripts/install-first

L'installazione è completata, ora resta solo da attivare il plugin dal pannello di amministrazione di ellg.

## Modifica Template

Ho usato un template system di nome **Handlebars**, la documentazione può essere trovata [qui]("http://handlebarsjs.com/"). In particolare ho sfruttato la sua caratteristica di poter precompilare i template, in modo da ottimizzare il caricamento una volta presi i dati dalle API.

Se si vogliono aggiungere templates o modificarli, si trovano nella cartella `foowd_theme/pages/templates `. 
Per generare i template AMD usare il seguente comando

	handlebars pages/templates/*.handlebars -f templates-amd.js --amd


### foowdAPI.js

foowdAPI.js è un modulo Javascript che contiene tutte le funzioni per interrogare le API foowd. In questo file sono contenute solo le chiamate alle API senza callback. Ho preferito inserirle qui in modo da scorporarle dal comportamento della singola pagina, a differenza della precedente versione.

### WallController.js
Gestisce tutte le azione relative alla pagina del wall-AMD.php, in file come questi vengono appunto richiamate le API e in questo caso usati i template di handlebars.

### ProductDetailController.js
Analogamente come il WallController gestisce il comportamento della pagina di dettaglio dei prodotti. Qui viene richiamata l'API per ottenere una singola offerta. L'id dell'offerta è passato dal WallController tramite parametro nell'URL.
