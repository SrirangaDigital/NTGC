#!/bin/sh

host="localhost"
db="ntgc"
usr="root"
pwd="mysql"

echo "drop database $db; create database $db charset utf8 collate utf8_general_ci;" | /usr/bin/mysql -uroot -pmysql

echo "Media Insertion begins";
perl insert_media.pl $host $db $usr $pwd
echo "Photo Insertion begins";
perl insert_photo.pl $host $db $usr $pwd
