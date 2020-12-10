#!/bin/bash
docker-compose up -d
docker-compose exec php bash -c 'cd backend; composer install;'
docker-compose exec php bash -c 'cd backend; bin/console doc:sch:cr;'
docker-compose exec php bash -c 'cd frontend; yarn install;'
docker-compose exec php bash -c 'cd frontend; yarn build;'