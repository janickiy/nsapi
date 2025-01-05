Шаблоное приложение для приложений, базируется на модулях quartz
===============================

Установка

1) git clone git@gitlab.freematiq.com:quartz/yii2-base-app.git

2) При развороте проекта можно использовать bash-скрипт (deploy/bash_init_project). Запуск: ```sudo ./baseapp.sh```
    Гарантировано работает на Ubuntu. На других OS не тестировалась
    Данная утилита сделает все за вас. 
    - создаст бд 
    - создаст nginx
    - создаст запись в hosts
    - создаст папку для логов nginx
    Если хочется сделать все это вручную - пожалуйста

3) Переходим в папку deploy

4) !!! При развороте проекта выполните инструкицю make first-init. Это разовое действие, которое вам предложить выбрать приложения, которые остануться в дальнейшем проекту (api, backend, frontend)
    Также, при выборе frontend приложения, будет предложено оставить локализацию фронтов или нет. Данный скрипт выполняется ровно один раз при
    первой сборке проекта (данный пункт удалить сразу после использования)

5) Запускаем make build-dev или build-prod, вводим все необходимые конфиги

6) Запустить команду make init-code-sniffer для включения анализатора кода

7) После чего доступны
    - /                 фронтед приложение. В SPA мы его выпиливаем обычно, но может понадобиться пожтому в базовой сборке оно есть
    - /admin            админ панель приложения  adminfmq@freematiq.com / passw0rd
    - /api              апи приложения
    - /api/swagger      Документация

8) Есть отдельная документация по swagger см. api/swagger/README.md

9) В приложении используется errorHandler из модуля [yii2-tools](http://gitlab.freematiq.com/quartz/yii2-tools/#Обработчик-ошибок-errorhandler-).
Более подбробно о нем на [вики](https://wiki.freematiq.com/pages/viewpage.action?pageId=11109470).

10) После первого разворота проекта поправьте все пути, названия и подсказки в deploy/bashapp.sh,
deploy/app_settings.json

11) !!! В /etc/nginx/nginx.conf добавить (раскоментить) строку server_tokens off

12) !!! В nginx конфиге вашего приложения добавить строку: error_page 403 /404;

13) В настройках Postgresql (/etc/postresql/9.*/main/postgresql.conf) установить параметр datestyle в 
```
datestyle = 'iso, dmy'
```

14) Мультидоменность

    Приложение доступно в мультидоменном формате:
    - http://base-app.local/admin заменено на http://admin.base-app.local
    - http://base-app.local/api заменено на http://api.base-app.local.
    
    Чтобы использовать такое приложение в пункте 2 вместо `baseapp.sh`, надо использовать ```sudo ./multi_domains.sh```
    
15) !!! Приложение содержит скрипт (fix-captcha-not-delete.js) и стили (fix-captcha-not-delete.css) для адаптивности блоков с reCAPTCHA. Не удалять!!!
16) В приложении дефолтные иконки пользователя лежат в backend и frontend, не удалять!
17) В приложение имеется анализатор кода [PHPStan](https://phpstan.org/)
    - Файл настроек находится в `deploy/standard/phpstan`
    - Проверка запускается при коммите совместно с CodeSniffer
    - Так же есть команда ручного запуска ```make phpstan```