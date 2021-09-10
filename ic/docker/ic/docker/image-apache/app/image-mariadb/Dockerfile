FROM mariadb:10.6

RUN apt update && \
	apt install -y -qq nano git

COPY ./sources/banque-symfony.sql /
COPY ./sources/init-mariadb.sh /