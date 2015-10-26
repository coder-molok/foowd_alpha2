#!/bin/bash

sudo chown -R http-web:http-web /var/www/html/
sudo chmod -R g+w /var/www/html/
find /var/www/html -type d -exec sudo chmod -v 2775 {} \;  
find /var/www/html/ -type f -exec sudo chmod -v ug+rw {} \;
