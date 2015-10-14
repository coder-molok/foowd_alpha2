# Foowd Theme

Questo è un tema sviluupato con **Elgg** per la piattforma **Foowd**. In particolare gestisce le pagine di 
> Wall dei prodotti
> Dettaglio di un prodotto
> Preferenze espresse dall'utente
> Pagina del produttore

# Installazione
Oltre all'installazione standard di un tema **Elgg**, la quale non viene trattata in questa documentazione, bisogna eseguire il comando: `bower install`
In modo da scaricare tutte le libreirie javascript utilizzate. 
**NOTA** : Per eseguire lo stesso script su un Virtual Server in alcuni casi e richiesto il flag `--allow-root`

# Librerie e Framework Utilizzati
> **JQuery** - Per animazioni e logica della UX
> **Handlebars** - Template AMD e Helpers
> **Masonry** - Wall engine
> **AnimOnScoll** - Personalizzazione del wall a comparsa
> **OwlCarusel** - Carosello immagini

# Strumenti per lo sviluppo utilizzati
> **Handlebars compiler** - per precompilare i template handlebars
> **Stylus** - pre-processore di stile
> **Jeet** - plugin per Stylus per utilizzare un grid system
> **Rupture** - plugin per stylus per un pagine responsive

# Logica del tema
La logica del tema si basa sul **require.js** imposto da Elgg. 
Ogni file e libreria Javascript e importata con un nome associato all'interno del file **start.php**. 
Ciascuna pagina del tema ha un file javascript dal nome strutturato in questo modo:

> ***NomeDellaPagina*Controller.js**

che ne gestisce la logica. Fatta eccezione per la barra di navigazione. Essendo un elemento presente in più pagine, il suo controller viene incluso quando richiesto all'interno delle pagine.

All'interno del file **start.php** si può notare che a fianco del path del file .js che si vuole includere all'interno dell'applicazione, c'è un array con le rispettive dipendenze del modulo .js che si è incluso all'interno dell'applicazione.
**EX:**
``` php
//no dependencies
elgg_define_js('jquery', [
'src' => 'mod/foowd_theme/vendor/jquery/dist/jquery.min.js',
]);

//deps is the array with the dependencies of the templates module
elgg_define_js('templates', [
'src' =>'/mod/foowd_theme/pages/templates/templates-amd.js',
'deps'=> array('handlebars','handlebars.runtime','helpers')
]);
```

#### Navigazione Tema
La navigazione all'interno del tema è gestita con i page handler di elgg. All'interno dello start.php si può trovare l'attuale mappatura delle pagine del tema.



# Struttura di un Controller

Tutti i file javascript sviluppati per questo tema seguono il **MODULE PATTERN**. Nello specifico i controller oltre a seguire questo design pattern sono stati scritti seguendo la stessa linea di pensiero. Infatti si possono trovare funzioni con nomi analoghi o quasi tra i vari pattern.
**Nota**: Le funzioni il quale nome e prefisso dal carattere **_** (underscore), sono idealmente considerate private del corrispettivo modulo.

#### Inizializzazione

La funzione *init* che ogni controller esporta, al suo interno si compone di due passaggi. 

 - **_stateCheck**, lancia il processo di inizializzazione del controller solo quando il DOM della pagina è stato completamente caricato.
 - **_init**, è la funzione di inizializzazione vera e propria del modulo.

#### Eventi
Dato che dopo l'inizializzazione del tema possono essere eseguite delle chiamate XHR per ottenere dati, la logica del tema al suo interno si sviluppa grazie a eventi custom, utilizzando la funzione **.trigger()** di JQ e inserendo gli opportuni listener che vanno a scatenare una certa logica su di un certo evento.

Ho scelto questo approccio in quanto avendo all'interno di questa applicazione **require.js**, c'è il rischio di eseguire della logica quando magari i dati necessari non sono ancora stati caricati.

# Gestione dei template
**Handlebars.js** è il template system che ho utilizzato per lo sviluppo di questo tema.
All'interno della cartella `foowd_theme\pages\templates` ci sono tutti i template utilizzati all'interno dell'applicazione.
####Come si utilizzano i template:

 1. Prima di tutto bisogna installare il compilatore di handlebars da linea di comando tramite npm: ``` npm install -g handlebars ``` (se hai una shell Win sono affari tuoi).
 2. Successivamente bisogna entrare da terminale nella cartella dei templates, in base all'ambiente di sviluppo il path può essere differente.
 3. Lanciare il comando : 
 ``` handlebars *.handlebars -f templates-amd.js --amd```
 4. Ora se non avete specificato un nuovo namespace per i template se all'interno del vostro scrip javascript includete tramite require il modulo dei template e utilizzarlo come di seguito: 
``` javascript
//loading templates
var templates = require('templates');
//template usage
var context = {
    firstName : "Jon",
    lastName  : "Doe"
};
var htmlContent = templates.nameOfYourTemplate(context); 
```

#### Nuovo Template
Se si necessita di un nuovo template basta creare un nuovo file all'interno della cartella templates e ri-compilare il tutto.
**NOTA:** il nome del template corrisponde al nome del file .js che contiene il template.

#### Helpers
Handlebars.js dispone di una feature che è quella degli **Helpers**. Come da documentazione sono dei tag aggiuntivi che si possono utilizzare per estendere la logica del template. Tutti gli helpers che sono stati creati, sono contenuti all'interno del file ```foowd_theme\lib\js\helpers.js```. 

**NOTA** dato che il tema include una versione lite di Handlebars, non è possibile utilizzare template paziali (partials). Per ovviare al problema il alcuni casi ho dichiarato un template e utilizzato esso all'interno di un helper.

# API
Il ```foowd_theme\lib\js\foowd\foowdAPI.js``` contiene un service per effettuare le varie chiamate alle API messe a disposizione del backend. Dato che in JS Vanilla non esistono le promise (o future), ho utilizzato la funzione **$.Deffered()** per creare delle funzioni che fossero asincrone. Come gli altri moduli anche questo e sviluppato secondo il Module Pattern, e per utilizzarlo lo si deve includere tramite require.

# Utility
Alcune funzionalità utili extra sono incluse nel file :  ```foowd_theme\lib\js\Utils.js``` e non sono vincolanti alla logica del tema. Dato che ne faccio largo uso all'interno del tema, ho pensato di esportare il modulo globalmente tramite l'oggetto **window** di javascript. Quindi nello script all'interno della pagina basta solo richiamare il modulo tramite require e sarà subito disponibile come modulo globale.

# NavabarController
Dato che questo modulo viene richiesto in tutte le pagine del tema, come il modulo utils ho deciso di esportarlo globalmente in modo da accedergli in maniera più rapida.
