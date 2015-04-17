Foowd Theme
===================

Questo è un tema sviluupato con **Elgg** per la piattforma **Foowd**.

Installazione
----------------
Per attivare il plugin bisogna prima di tutto installare **Elgg** in un prorpio virtual server locale
> **Nota :** per esempio Apache.


Successivamente bisogna copiare la cartella **foowd_theme** in :
>``` <path/to/elgg>/mod/ ```


Alternativamente si può creare un link simbolico con il comando:


>```ln -s path/to/mod/folder/ path/to/elgg/mod/folder/```


In questo modo si può modifcare il plugin senza tutte le volte aggiornare quello all'interno della cartella mods.

Successivamente a seconda del passaggio precedente, bisogna accedere alla cartella della mod ed eseguire **Composer**:
>```composer update ```
> ```composer dump-autoload```

Oltre ai moduli di composer bisogna anche installare alcuni moduli css e javascript tramite bower eseguendo il comando:
>```bower install ```


