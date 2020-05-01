#!/bin/bash
##############################
echo "<<<<<<<<<<<<<<<<< Set files permissions"
chmod -R 777 var/log/
#############################
echo "<<<<<<<<<<<<<<<<<<<< Create folder for JWT public key"
mkdir -p config/jwt
chmod -R 755 config/jwt
#############################