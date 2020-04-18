#!/bin/bash
#############################
echo "<<<<<<<<<<<<<<<<<<<<< Run migrations"
php bin/console --no-interaction doctrine:migrations:migrate
php bin/console --env=test --no-interaction doctrine:migrations:migrate
#############################
echo " <<<<<<<<<<<<<<<<<<<< Generate JWT Keys"
jwt_pass=$(grep JWT_PASSPHRASE .env | xargs)
jwt_pass=${jwt_pass#*=}

mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass pass:$jwt_pass
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout -passin pass:$jwt_pass
chmod -R 644 config/jwt/*
#############################