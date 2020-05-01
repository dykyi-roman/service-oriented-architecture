#!/bin/bash
#############################
echo "<<<<<<<<<<<<<<<<<<<< Share storage sfolder"
chown -R www-data:www-data ./storage/
#############################
echo "<<<<<<<<<<<<<<<<<<<< Create folder for JWT public key"
mkdir -p config/jwt
chmod -R 755 config/jwt
#############################
