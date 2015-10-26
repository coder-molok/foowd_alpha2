#!/bin/bash

sudo chgrp -R html-web:html-web /var/www/html/
sudo chmod -R g+w /var/www/html/
find /var/www/html -type d -exec chmod 2775 {} \;  
find /var/www/html/ -type f -exec chmod ug+rw {} \;
