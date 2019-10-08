#!/usr/bin/env bash

cd docker
docker-compose exec php bin/console bot:telegram:run
