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

> NB:
>       per ogni variabile rappresentante il selettore, il plugin memorizza l'elemento jQuery nel formato `J{variabile}`


