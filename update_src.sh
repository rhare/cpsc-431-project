container_id=`docker ps | grep php-apache | awk '{print $1}'`
docker cp src/. $container_id:/var/www/html/
