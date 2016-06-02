#!/bin/bash

function changePermission(){
	if [ "`basename "$1"`" == "foowdTask.sh" ]; then
		sudo chmod -v ug+rwx "$1"
	else
		sudo chmod -v ug+rw "$1"
	fi
}
export -f changePermission

sudo chown -R http-web:http-web /var/www/html/
sudo chmod -R g+w /var/www/html/
find /var/www/html -type d -exec sudo chmod -v 2775 {} \;  
find /var/www/html/ -type f -exec bash -c 'changePermission "{}"' \;
