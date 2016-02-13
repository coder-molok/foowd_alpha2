GUI
===

l'interfaccia del plugin `jquery-ui` viene sovrascritta tramite il download di un tema custom dalla pagina

[http://jqueryui.com/themeroller/](http://jqueryui.com/themeroller/).

Una volta scelto il download, nella schermata che appare e' necessario impostare **ui-theme-foowd** come valore **Theme Folder Name**


Visto che e' piuttosto pesante, per non rendere gravosi i commit, scelgo che l'estrazione avvenga quando in foowdSync.sh si imposta l'opzione -f: viene estratto juery.ui.custom.zip