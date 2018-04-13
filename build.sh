set -ex

# Building from cache means file changes wont be availalbe in the container. No cahcne ensures file changes are made
# in the container.
#
# Alternatively, we can write a scripot that copies the files from localhost to the docker container while it
# is running.
docker build -t php-7.2.1-apache-project . --no-cache

