#!/bin/bash
if [ ! -d "./src/Command" ]; then
    cp -r ./vendor/uicms/app/install/src/Command ./src/
fi