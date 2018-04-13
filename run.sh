#!/bin/bash
# Take down the running containers defined in docker-compose.yaml
docker-compose down

# Build a new container which includes file changes
./build.sh

# Bring up the containers defined in dockder-compose.yaml
docker-compose up -d

# Give mariadb some time to start up.
sleep 10

# Connect to mariadb
./create_populate_db.sh
