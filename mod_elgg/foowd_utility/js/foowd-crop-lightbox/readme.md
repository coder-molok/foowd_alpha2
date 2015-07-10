# foowd-crop-lightbox

plugin per il crop di immagini e il salvataggio dei parametri di crop in un opportuno elemento html.

#### Prerequisiti

il plugin utilizza:
- elgg
- jquery
- imagAreaSelect

# Promemoria di produzione

### setInit()

funzione chiamata da `OBJECT.initialize()`all'inizio posso passare i seguenti parametri:

##### obbligatori

- `urlF` , l'url della pagina che carica l'immagine e la restituisce come stringa

- `fileInput` , il selettore Jquery  del campo input che immagazzina i file,ovvero le immagini. Deve essere gia' esistente o caricato prima dell'esecuzione della funzione

- `loadedImgContainer` selettore Jquery che immagazzinera', se gia' non esiste. Deve essere gia' esistente o caricato prima dell'esecuzione della funzione

- `sourceImg` , selettore jQuery del tag img che funge da sorgente. Se esiste lo carica nel membro `Jimg`  del plugin, altrimenti lo creera' col caricamento dell'immagine

- `imgContainer` , ????

##### opzionali

- `css` , array contenente i path dei fogli di stile da caricare. Vengono caricati solo se non sono gia' presenti



### Membri dell'oggetto creati

> NB:
>       per ogni variabile rappresentante il selettore, il plugin memorizza l'elemento jQuery nel formato `J{variabile}`


> NB2:
>   tutti i parametri di inizializzazione vengono assegnati ai rispettivi parametri del plugin: `this.{parametro} = init.{parametro}`     


- `Jimg` , l'immagine salvata come elemento jQuery (il tag `img`)

- `nocss`, default false. Serve per non eseguire l'incorporamento dei files css utili al plugin (come comportamento di default). Vedere anche l'opzione di inizializzazione `css`

- `preWindows` , array contenente gli oggetti *finestre di preview*, ovvero la classe `Prewindows()`

- `ias` , istanza di `imagAreaSelect`

-  `Jcrop` , oggetto contenente gli input in cui salvare i parametri del crop (x1,x2,y1,y2)


prevclass per imagAreaSelect e prevwindow

