# cpsc-431-project (placeholder)
CPSC-431 Project

## Description
Empty project


I have include my homework 3 as a base to start with. I have included Docker files to run the project in dokcer
containers. The Docker files build a custom container with the source code and php + apache and launch the container
along with a MariaDB container.


## Install
I have included a Dockerfile, docker-compose file, and a few scripts. You may choose to use these.
If you do not have Docker, it's simple to [get started.](https://docs.docker.com/get-started/)


```bash
./run.sh
```
run.sh will do a few things. It will stop and destroy current running docker containers, rebuild the docker container,
populate the database, and relaunch the docker container. Read the script to see how it works. 

## Contributers
Adam K Brainich
Huy Le
Robert Hare
