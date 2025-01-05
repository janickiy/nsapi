# Конфиг nginx для веб-версии инсталятора
server {
    charset utf-8;
    client_max_body_size 128M;
    listen <?= $_SERVER['SERVER_PORT'] ?>; ## listen for ipv4

    server_name <?= $_SERVER['SERVER_NAME'] ?> <?= 'www.'.$_SERVER['SERVER_NAME']?>;
    root        <?= $project_dir ?>;
    index       index.php;

    access_log  <?= $log_dir.'/nginx_access.log' ?>;
    error_log   <?= $log_dir.'/nginx_error.log' ?>;

    location / {
        root <?= $project_dir.'/frontend/web' ?>;
        try_files $uri /frontend/web/index.php?$args;
        # avoiding processing of calls to non-existing static files by Yii
        location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
            access_log  on;
            expires  360d;

            try_files  $uri =404;
        }
    }

    location /admin {
        alias  <?= $project_dir.'/backend/web' ?>;

        rewrite  ^(/admin)/$ $1 permanent;
        try_files  $uri /backend/web/index.php?$args;
    }

    location /upload/{
        root <?= $project_dir ?>;
    }

    location ~ ^/admin/(.+\.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar))$ {
        access_log  on;
        expires  360d;

        rewrite  ^/admin/(.+)$ /backend/web/$1 break;
        rewrite  ^/admin/(.+)/(.+)$ /backend/web/$1/$2 break;
        try_files  $uri =404;
    }


    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        #
        #	# With php7.0-cgi alone:
        #	fastcgi_pass 127.0.0.1:9000;
        # With php7.0-fpm:
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }
}