#!/bin/bash
set -o allexport
source .env
set +o allexport

echo "Creating bridge network: $BRIDGE_NETWORK"

docker network create \
    --driver=bridge \
    --subnet=$BRIDGE_NETWORK_ADDRESS.0.0.0/16 \
    --gateway=$BRIDGE_NETWORK_ADDRESS.0.0.254 \
    $BRIDGE_NETWORK

if grep -Fxq "$NGINX_ALIAS" /etc/hosts; then
    echo "$NGINX_ALIAS already exists in /etc/hosts!"
else
    echo "$NGINX_IP      $NGINX_ALIAS" >> /etc/hosts  
    echo "/etc/hosts updated"
fi

cp nginx/arb-443.conf.tmpl nginx/conf.d/arb-443.conf
sed -i "s/%NGINX_ALIAS%/$NGINX_ALIAS/g" nginx/conf.d/arb-443.conf

cp laravel_backend/.env.tmpl laravel_backend/.env
sed -i "s/%NGINX_ALIAS%/$NGINX_ALIAS/g" laravel_backend/.env
sed -i "s/%DB_IP%/$DB_IP/g" laravel_backend/.env
sed -i "s/%DB_NAME%/$DB_NAME/g" laravel_backend/.env
sed -i "s/%DB_USERNAME%/$DB_USERNAME/g" laravel_backend/.env
sed -i "s/%DB_PASSWORD%/$DB_PASSWORD/g" laravel_backend/.env