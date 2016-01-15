#!/bin/bash 

#########################################################################################
### questo script svolge la sincronizzazione del repo foowd_alpha2 su VPS di sviluppo
###
### Di default si limita a runnare rsync con sudo, impostando i dovuti permessi ai files,
### pertanto accertarsi che l'utente con cui si esegue abbia i dovuti permessi.
###
### opzioni da riga di comando
###
###         -f  ->  forza l'opzione --delete di rsync
###                 e l'esecuzione di composer e bower nelle directory mod_elgg
###
#########################################################################################

### funzione di check dei parametri passati allo script ###
OPTIONS=("${@}")
function optionExists(){
    local e
    # 1 e' false, 0 e' true
    local CHECK=1
    for e in ${OPTIONS[*]}; do
        if [[ "$e" == "$1" ]]; then CHECK=0; fi
    done
    return "$CHECK"
}

#test
#if optionExists "--force-file" ; then echo "ok force"; fi
### Fine funzione di Check ###

              
# run only as root                     
#if [[ $EUID -ne 0 ]]; then             
#   echo -e "\e[41m This script must be run as root \e[m" 1>&2   
#   exit 1     
#fi            
              
              
### Directory principali               
REPO=""   
SITE="/var/www/html/elgg-1.10.4/"
API="/var/www/html/api_foowd"
ModPath=$SITE"/mod/"        


### costruzione del comando di base
if optionExists "-f" ; then FORCE=true; else FORCE=false; fi
if $FORCE ;
then
    # excluding path
    EXCLUDE="--exclude-from=${REPO}script/rsyncExclude.config"
    #eventualmente opzione -n per testare prima che svolga
    DEL=" --delete"
else
    EXCLUDE=""
    DEL=" "
fi
CMD="sudo rsync -a -v ${EXCLUDE} --chown=http-web:http-web"

              
              
### Git       
echo -e "\n\e[45m copying from repo in $REPO \e[m"       
#(cd "$REPO"; git status; git pull)     
              
              
### MOD_ELGG  
echo -e "\n\e[45m Mod_Elgg part \e[m"                  

for D in $REPO"mod_elgg/"*; do         
    if [ -d "${D}" ]; then         
        SRC=`echo ${D}/`       
        DST=`basename ${D}`
        TMPCMD="$CMD"
        EXTRACMD=""
        # se la directory contiene bower.json, allora lo runno 
        if [ -f "${D}/bower.json" ] && $FORCE ; then                 
            TMPCMD="$TMPCMD$DEL"
            EXTRACMD="$EXTRACMD ; (cd $ModPath$DST; echo 'runno bower...'; sudo bower install --allow-root )"              
        fi
        # se la directory contiene composer.json, allora lo runno 
        if [ -f "${D}/composer.json" ] && $FORCE ; then
            TMPCMD="$TMPCMD$DEL"
            EXTRACMD="$EXTRACMD ; (cd $ModPath$DST; echo 'runno composer...'; sudo composer install )"              
        fi

        TMP="$TMPCMD $SRC $ModPath$DST $EXTRACMD"                        
        
        eval "$TMP"            
        #echo "$TMP"           
    fi    
done          
              
              
### API_FOOWD 
echo -e "\n\e[45m Api_Foowd part \e[m" 
echo "aggiorno api_foowd"              
SRC=$REPO"api_foowd/"                  
DST="$API"            
$CMD $SRC $DST    

