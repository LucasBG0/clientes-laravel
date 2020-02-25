#!/bin/bash

echo Uploading Application container 
docker-compose up -d

#echo Copying the configuration example file
#docker exec gerenciador-clientes-app cp .env.example .env

echo Install dependencies
docker exec gerenciador-clientes-app composer install

echo Generate key
docker exec gerenciador-clientes-app php artisan key:generate

echo Make migrations
docker exec gerenciador-clientes-app php artisan migrate

echo Make seeds
docker exec gerenciador-clientes-app php artisan db:seed --force

echo Link storage
docker exec gerenciador-clientes-app php artisan storage:link

echo Information of new containers
docker ps -a 