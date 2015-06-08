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

Installare con npm bower

	npm install -g bower

Installiamo alcuni moduli Javascript con  
	
	bower install

L'installazione è completata, ora resta solo da attivare il plugin dal pannello di amministrazione di ellg.

## Modifica Template

Ho usato un template system di nome **Handlebars**, la documentazione può essere trovata [qui]("http://handlebarsjs.com/"). In particolare ho sfruttato la sua caratteristica di poter precompilare i template, in modo da ottimizzare il caricamento una volta presi i dati dalle API.

Se si vogliono aggiungere templates o modificarli, si trovano nella cartella `foowd_theme/pages/templates `. 
Una volta modificati bisogna procedere con la precompilazione eseguendo il comando : 

	handlebars pages/templates/<templatefile>.handlebars -f pages/templates/templates.js 

Nel caso ci siano più di un template da compilare bisogna dare in input, prima del ```-f```, tutti i file con estensione ```.handlebars```.

Per utilizzare **Handlebars** suggerisco di installarlo tramite :

	npm install -g handlebars 

> **Nota Template :** installare Handlebars in riga di comando serve soltanto se si intende modificare i template. **Non è essenziale per il funzionamento del plugin**

Per generare i template AMD usare il seguente comando

	handlebars <template1.handlebars> <template2.handlebars> -f templates-amd.js --amd



### foowdAPI.js

foowdAPI.js è un modulo Javascript che contiene tutte le funzioni per interrogare le API foowd. In questo file sono contenute solo le chiamate alle API senza callback. Ho preferito inserirle qui in modo da scorporarle dal comportamento della singola pagina, a differenza della precedente versione.

### WallController.js
Gestisce tutte le azione relative alla pagina del wall-AMD.php, in file come questi vengono appunto richiamate le API e in questo caso usati i template di handlebars.

### ProductDetailController.js
Analogamente come il WallController gestisce il comportamento della pagina di dettaglio dei prodotti. Qui viene richiamata l'API per ottenere una singola offerta. L'id dell'offerta è passato dal WallController tramite parametro nell'URL.
