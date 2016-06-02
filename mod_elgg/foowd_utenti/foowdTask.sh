#!/bin/bash

#####################################################################
# Script che raccoglie un elenco di task per la generazione di file.
# 
# Ogni volta che si esegue un aggiornamento o un merge da github, 
# e' conveniente runnare le opzioni-All (ad esempio compileAll),
# per aggiornare i files che vengono generati dai compilatori.
# 
# Per semplificare l'aggiornamento basta utilizzare il task "updateAll"
# che svolge tutte le operazioni necessarie alla generazione
# di tutti i file necessari.
# 
#####################################################################

SCRIPT=`realpath $0`
PLUGDIR=`dirname $SCRIPT`

function makeTask(){
    pushd "$PLUGDIR"
    case  "$1" in
        "compileCss")
            # Compilo i css
            stylus -u nib "css/foowd-utenti.styl"
        ;;
        "compileCoffee")
            # Compilo i javascript coffee
            pwd
            coffee -c -o views/default/js/foowd_utenti/ views/default/js/foowd_utenti/coffee/
        ;;
        "compileAll")
            # compilo tutto con un comando solo: utile per aggiornare tutto dopo un pull
            makeTask compileCss
            makeTask compileCoffee
        ;;
        "updateAll")
            makeTask compileAll
        ;;
    esac
    popd
}

# Posso passare un singolo task oppure un elenco di task
# 
#   makeTask <task1> <task2> <task3> ....
#   
for task in "$@"
do
    makeTask "$task"
done