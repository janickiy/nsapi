#!/bin/bash

#
# Скрипт автоматической генерации настроек nginx, создания БД, папки для логов, прокиыдвание домена в хосты и перезапуск сервера
#

#Получаем путь до проекта
echo "Введите путь до проекта (например /var/www/yii2-base-app)"
read project_path

if [ ! -d $project_path ]
then
	echo "Каталог с проектом не найден!"
	exit 0
fi

#Получаем имя домена
echo "Введите доменное имя (например base-app.local)"
read domain
#Получаем путь до логов
echo "Введите путь каталога логов (например /var/www/log/base-app)"
read log
#Получаем имя БД
echo "Введите имя БД (например base-app)"
read db
#Получаем версию php
echo "Введите версию php (например 7.3)"
read php_version

#Получаем имя для конфиг файла из доменного имени
separated_domain=`echo "$domain" | cut -d '.' -f 1`

if [ ! -f /etc/nginx/sites-available/$separated_domain.conf ]
then
	#Копируем шаблон конфига nginx
	cp nginx.conf /etc/nginx/sites-available/$separated_domain.conf

	#Экранируем пути и заменям их в конфиге
	screened_path=${project_path//\//\\/}
	screened_log=${log//\//\\/}
	sed -i "s/{domain}/$domain/" /etc/nginx/sites-available/$separated_domain.conf
	sed -i "s/{path}/$screened_path/" /etc/nginx/sites-available/$separated_domain.conf
	sed -i "s/{log}/$screened_log/" /etc/nginx/sites-available/$separated_domain.conf
	sed -i "s/{php_version}/$php_version/" /etc/nginx/sites-available/$separated_domain.conf
else echo "Конфигурация /etc/nginx/sites-available/$separated_domain.conf не создана, т.к. уже существует"
fi

if [ ! -f /etc/nginx/sites-enabled/$separated_domain.conf ]
then
	#Создаем ссылку на конфигурационный файл
	ln -s /etc/nginx/sites-available/$separated_domain.conf /etc/nginx/sites-enabled/$separated_domain.conf
else echo "Ссылка /etc/nginx/sites-enabled/$separated_domain.conf не создан, т.к. уже существует"
fi

if [ ! -d $log ]
then
	#Создаем папку для логов
	mkdir $log
else echo "Директория $log не создана, т.к. уже существует"
fi

if ! grep -q $domain /etc/hosts
then
	#Редактируем /etc/hosts
	sed -i -e "1 s/^/127.0.0.1\t$domain\n/;" /etc/hosts
else echo "Хост не был создан, т.к. уже существует в /etc/hosts"
fi

#Создаем БД
sudo -u postgres createdb $db
#Перезапускаем nginx
service nginx restart

echo "Была создана конфигурация для проекта $project_path с доменом $domain. Логи будут содержаться в $log. Создана БД с именем $db"

exit 0
