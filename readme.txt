Per il servizio php servirà una cartella mio-sito che conterrà i file che utilizzati all'accesso dell'URL "http://localhost:9000"
Per avviare il servizio PHP:

docker run -d -p 9000:80 --name my-apache-php-app --rm -v /home/informatica/mio-sito:/var/www/html zener79/php:7.4-apache

Per il data access layer bisogna creare una cartella dump, la cartella dovrà contenere un file per l'inizializzazione dei dati del database.
Per avviare il servizio del Database:

docker run --name my-mysql-server --rm -v /home/informatica/mysqldata:/var/lib/mysql -v /home/informatica/dump:/dump -e MYSQL_ROOT_PASSWORD=my-secret-pw -p 3306:3306 -d mysql:latest

NOTA: home e informatica potrebbero variare di nome


Per entrare nell'immagine di MySQL: 
	docker exec -it my-mysql-server bash

Per poter utilizzare il database con SQL: 
	mysql -u root -p
	password: my-secret-pw
