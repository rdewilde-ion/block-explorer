# ION BLOCK EXPLORER

Docker running Nginx, PHP-FPM, Composer, MySQL and IOND to provide block explorer.

Need to install bcmath PHP-extension manually thru `docker-php-ext-install bcmath`. To do this, look for the php container ID using `docker ps`. Then execute the command on the container `docker exec -ti 1234567id docker-php-ext-install bcmath`.

Cronjobs need to be installed manually:
```
* * * * * docker exec -i bc002c05fe0e php public/index.php /cli/buildDatabase
*/30 * * * * docker exec -i bc002c05fe0e php public/index.php /cli/buildRichlist
15 * * * * docker exec -i bc002c05fe0e php public/index.php /cli/getNetworkInfo

```
(don't forget newline).

Be sure crond service is running.
