---

Установка
==========

Этапы установки:
--------------

1. Настроить nginx для тестового сервера:

   Пример конфига:

   ```
     server {
         charset utf-8;
         client_max_body_size 128M;
         listen 80; ## listen for ipv4
         #listen [::]:80 default_server ipv6only=on; ## listen for ipv6
     
         server_name baseapp-test.local;
         root        /var/www/baseapp;
         index       index.php;
     
         access_log  /var/log/baseapp/access.log;
         error_log   /var/log/baseapp/error.log;
     
         location / {
             root /var/www/baseapp/frontend/web;
             try_files $uri /frontend/web/index-test.php?$args;
     
             # avoiding processing of calls to non-existing static files by Yii
             location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar|woff|woff2|svg)$ {
                 access_log  on;
                 expires  360d;
     
                 try_files  $uri =404;
             }
         }
     
         location /admin {
             alias  /var/www/baseapp/backend/web;
     
             rewrite  ^(/admin)/$ $1 permanent;
             try_files  $uri /backend/web/index-test.php?$args;
         }
     
         location /upload/{
             root /var/www/baseapp;
         }
     
         location ~ ^/admin/(.+\.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar|woff|woff2|svg))$ {
             access_log  on;
             expires  360d;
     
             rewrite  ^/admin/(.+)$ /backend/web/$1 break;
             rewrite  ^/admin/(.+)/(.+)$ /backend/web/$1/$2 break;
             try_files  $uri =404;
         }
     
         location /api {
             root  /var/www/baseapp/api/web;
             rewrite  ^(/api)/$ $1 permanent;
             try_files  $uri /api/web/index-test.php?$args;
         }
     
         location ~ \.php$ {
             include snippets/fastcgi-php.conf;
             #
             #	# With php7.0-cgi alone:
             #	fastcgi_pass 127.0.0.1:9000;
             # With php7.0-fpm:
             fastcgi_pass unix:/run/php/php7.1-fpm.sock;
         }
     
         location ~ /\.(ht|svn|git) {
             deny all;
         }
     }

   ```

3. Создать тестовую БД. Например `baseapp-test`.

4. Меняем урлы в файлах, на урлы вашего тестового приложения. Файлы:
    * frontend/tests/acceptance.suite.yml
    * backend/tests/acceptance.suite.yml

Структура тестов
----------------

В данной реализации, тесты разделены на приложения api, backend, frontend и т.д. Каждое приложение имеет свои сценарии, данные фикстур, конфиги.
Также имеются тесты в приложении common, содержащие общие тесты для всех приложений
Помимо тестов, common, как правило, содержит все фикстуры, необходимые для работы (Но никто не запрещает размещать их на уровне приложений)

Сборка тестов
--------------
Прописать в `backend\test\unit.suite.yml`  настройки базы данных, имя бд, имя пользователя и пароль.

Для удобной сборки тестов, были созданы команды в deploy/Makefile, а именно:
* test-build - накатывает миграции, парсит переводы, собирает тесты
* test-run - запускает все тесты

При необходимости, можно собирать тесты и запускать их в ручную из корня:
* /vendor/bin/codecept build
* /vendor/bin/codecept run

Также можно запускать тесты отдельного приложения командой:

`vendor/bin/codecept run -- -c backend`
  
И даже отдельный вид тестов отдельного приложения:

`vendor/bin/codecept run unit -- -c frontend`

Приемочные тесты:

Для работы приемочных тестов (с эмуляцией работы браузера) необходимо запустить так называемый тестовый эмуляционный сервер seleniumServer. Также для работы 
необходимо скачать драйвер для эмуляционного браузера. 
Рабочий вариант Selenium и драйвера для хрома вы можете скачать по ссылке: https://yadi.sk/d/Qh-x3LOw3T6wEe (Проверено для Chromium 64.0.3282.167)

Запускать сервер необходимо следующей командой:
  `java -jar selenium-server-standalone-2.42.1.jar -Dwebdriver.chrome.driver=chromedriver`
  
Примеры
-------
На данный момент реализованы примеры всех типов тестов (api, unit, functional, acceptance) для всех приложений (api, backend, common, frontend).
Тесты носят ознакомительный характер и примеры их использования.
Если вы найдете полезные приемы, впомогательные классы и библиотеки или сами напишите чудо-штуку - обязательно добавьте это в базову сборку

