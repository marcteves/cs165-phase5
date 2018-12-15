#!/usr/bin/env bash
# Resets the database and starts the HTTP server

echo "DROP DATABASE iskolivery;" | mysql -u root

# mysql password must be unset
mysql -u root < database_setup.sql
lighttpd -D -f lighttpd.conf
