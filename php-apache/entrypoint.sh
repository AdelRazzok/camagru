#!/bin/bash

chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
mkdir -p /var/www/html/uploads
chown -R www-data:www-data /var/www/html/uploads
chmod -R 777 /var/www/html/uploads

apache2-foreground
