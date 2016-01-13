#!/bin/bash 
              
              
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


# excluding path
EXCLUDE="--exclude-from=${REPO}script/rsyncExclude.config"
CMD="sudo rsync -a -v ${EXCLUDE} --chown=http-web:http-web"                       
              
              
### Git       
echo -e "\n\e[45m copying from repo in $REPO \e[m"       
#(cd "$REPO"; git status; git pull)     
              
              
### MOD_ELGG  
echo -e "\n\e[45m Mod_Elgg part \e[m"  
#eventualmente opzione -n per testare prima che svolga          
DEL=" --delete"                        
              
for D in $REPO"mod_elgg/"*; do         
    if [ -d "${D}" ]; then         
        SRC=`echo ${D}/`       
        DST=`basename ${D}`
        TMPCMD="$CMD"
        EXTRACMD=""
        # se la directory contiene bower.json, allora lo runno 
        if [ -f "${D}/bower.json" ]; then                 
            TMPCMD="$TMPCMD$DEL"
            EXTRACMD="$EXTRACMD ; (cd $ModPath$DST; echo 'runno bower...'; bower install --allow-root )"              
        fi
        # se la directory contiene composer.json, allora lo runno 
        if [ -f "${D}/composer.json" ]; then
            TMPCMD="$TMPCMD$DEL"
            EXTRACMD="$EXTRACMD ; (cd $ModPath$DST; echo 'runno composer...'; composer install )"              
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


