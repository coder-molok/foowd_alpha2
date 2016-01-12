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
CMD="sudo rsync -a -v  --chown=http-web:http-web"                       
              
              
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
        if [[ "${D}" == *theme ]]; then                 
            TMP="$CMD$DEL $SRC $ModPath$DST ; (cd $ModPath$DST; echo 'runno  
bower...'; bower install --allow-root )"              
        else                   
            TMP="$CMD $SRC $ModPath$DST"   
        fi                     
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


