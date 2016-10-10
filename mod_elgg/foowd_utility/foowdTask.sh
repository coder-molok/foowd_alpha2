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
        "compileCoffee")
            # Compilo i javascript coffee
            IFS=$'\n';for f in $(find ./js -name '*.coffee'); do coffee -c $f; done
        ;;
        "compileAll")
            # compilo tutto con un comando solo: utile per aggiornare tutto dopo un pull
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