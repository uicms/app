#!/bin/bash
if [ ! -d "./public/themes" ]; then
    mkdir ./public/themes
fi
if [ ! -d "./public/themes/app" ]; then
    cp -r ./vendor/uicms/app/install/public/themes/app ./public/themes/
fi
if [ ! -d "./src/Migrations" ]; then
    mkdir ./src/Migrations
fi
if [ ! -d "./src/Services" ]; then
    mkdir ./src/Services
fi
cp ./vendor/uicms/app/install/bin/entity ./bin/
cp ./vendor/uicms/app/install/bin/migrate ./bin/
cp -r ./vendor/uicms/app/install/src/EventListener ./src/
cp -r ./vendor/uicms/app/install/src/Command ./src/
rsync -avz --delete ./vendor/uicms/app/install/config/ ./config/
rsync -avz --delete ./vendor/uicms/app/install/src/Controller/ ./src/Controller/
rsync -avz --delete ./vendor/uicms/app/install/src/Entity/ ./src/Entity/
rsync -avz --delete ./vendor/uicms/app/install/src/Repository/ ./src/Repository/
rsync -avz --delete ./vendor/uicms/app/install/src/Services/ ./src/Services/