<?php
$_ENV['YII_PRODUCT_SETTINGS'] =
    \yii\helpers\Json::decode(file_get_contents(dirname(dirname(__DIR__)) . '/settings.json'));

require_once(__DIR__ . '/plug-settings.php');

$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ],
    'bootstrap' => [
        'mailnotifyQueue',
    ],
    'components' => [
        'db' => require(__DIR__ . '/db.php'),
        'mailer' => require(__DIR__ . '/mail.php'),
        'i18n' => require(__DIR__ . '/i18n.php'),
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@backend/runtime/cache',
            'dirMode' => 0777,
            'fileMode' => 0777,
        ],
        'user' => [
            'class' => quartz\user\components\web\User::class,
            'enableAutoLogin' => false,
            'loginUrl' => ['auth'],
            'authTimeout' => $_ENV['YII_PRODUCT_SETTINGS']['common']['session_lifetime'],
        ],
        'authManager' => [
            'class' => 'quartz\rbac\models\DbManager',
            'defaultRoles' => ['Guest'],
        ],
        'formatter' => [
            'dateFormat' => 'php:d.m.Y',
            'timeFormat' => 'php:H:i:s',
            'datetimeFormat' => 'php:d.m.Y H:i:s',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'nullDisplay' => true
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'cache' => 'cache',
            'timeout' => $_ENV['YII_PRODUCT_SETTINGS']['common']['session_lifetime'],
        ],
        'log' => [
            'class' => 'quartz\tools\modules\errorHandler\components\log\Dispatcher',
            'logger' => [
                'class' => 'quartz\tools\modules\errorHandler\components\log\Logger'
            ]
        ],
        'response' => [
            'class' => 'quartz\tools\modules\errorHandler\components\web\Response',
            'on beforeSend' => function ($event) {
                // только в режиме dev даём возможность просмтривать меню yii
                !YII_ENV_DEV ? $event->sender->headers->add('X-Frame-Options', 'DENY') : null;
                $event->sender->headers->add('X-Content-Type-Options', 'nosniff');
                $event->sender->headers->add('X-XSS-Protection', '1; mode=block');
                $event->sender->headers->add(
                    'Content-Security-Policy',
                    "default-src 'none';"
                    . "script-src 'self' 'unsafe-eval' 'unsafe-inline' http://www.google.com https://www.gstatic.com;"
                    . "style-src 'self' 'unsafe-inline' *.googleapis.com *.fontawesome.com;"
                    . "img-src 'self' data: blob:;"
                    . "object-src 'self' data: blob:;"
                    . "connect-src 'self' https://cdn.rawgit.com;"
                    . "frame-src 'self' http://www.google.com https://www.google.com;"
                    . "font-src 'self' data: *.fontawesome.com https://fonts.gstatic.com http://fonts.gstatic.com"
                );
            },
        ],
        'mailnotifyQueue' => [
            'class' => \yii\queue\amqp_interop\Queue::class,
            'port' => $_ENV['YII_PRODUCT_SETTINGS']['MQ']['port'],
            'host' => $_ENV['YII_PRODUCT_SETTINGS']['MQ']['host'],
            'vhost' => $_ENV['YII_PRODUCT_SETTINGS']['MQ']['vhost'],
            'user' => $_ENV['YII_PRODUCT_SETTINGS']['MQ']['user'],
            'password' => $_ENV['YII_PRODUCT_SETTINGS']['MQ']['password'],
            'queueName' => $_ENV['YII_PRODUCT_SETTINGS']['MQ']['prefix']
                . $_ENV['YII_PRODUCT_SETTINGS']['MQ']['send_mail'],
            'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
            'exchangeName' => $_ENV['YII_PRODUCT_SETTINGS']['MQ']['prefix'] . 'send-mail-exchange',
        ],
    ],
    'modules' => [
        'mailnotify' => [
            'class' => 'quartz\mailnotify\MailnotifyModule',
            'useActiveMQ' => true,
            'emailModerator' => $_ENV['YII_PRODUCT_SETTINGS']['common']['supportEmail'],
            'externalMessageSource' => true,
            'components' => [
                'sender' => [
                    'class' => 'quartz\mailnotify\components\SenderComponent',
                ],
            ],
            'imageUrl' => $_ENV['YII_PRODUCT_SETTINGS']['common']['domain'] . '/upload/mailnotify',
        ],
        'user' => [
            'class' => 'quartz\user\User',
            'externalMessageSource' => true,
            'reCapchaSecret' => $_ENV['YII_PRODUCT_SETTINGS']['capcha']['reCapchaSecret'],
            'reCapchaSiteKey' => $_ENV['YII_PRODUCT_SETTINGS']['capcha']['reCapchaSiteKey'],
        ],
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
            'dbSettings' => [
                'tableName' => 'dynagrid',
                'filterAttr' => 'filter_id',
                'sortAttr' => 'sort_id'
            ],
            'dbSettingsDtl' => [
                'tableName' => 'dynagrid_dtl'
            ]
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'settings' => [
            'class' => 'quartz\settingsModule\Settings',
            'externalMessageSource' => true,
            'components' => [
                'settingsComponent' => [
                    'class' => 'quartz\settingsModule\components\SettingsComponent'
                ]
            ]
        ],
        'localization' => [
            'controllerNamespace' => 'backend\controllers\localization',
            'class' => 'quartz\localization\Localization',
            'useCache' => true,
            'timeCache' => 3600,
            'externalMessageSource' => true,
        ],
        'adminlteTheme' => [
            'class' => 'quartz\adminlteTheme\AdminlteTheme',
        ],
        'permit' => [
            'class' => 'quartz\rbac\RbacControlModule',
            'externalMessageSource' => true,
        ],
        'fileapi' => [
            'class' => 'quartz\fileapi\Fileapi',
        ],
        'multiple-upload' => [
            'class' => 'quartz\multipleUpload\MultipleUpload',
        ],
    ],
];


if ($_ENV['YII_PRODUCT_SETTINGS']['common']['useHttps']) {
    $config['components']['user']['identityCookie'] = [
        'name' => '_identity',
        'httpOnly' => true,
        'secure' => true
    ];

    $config['components']['session']['cookieParams'] = [
        'httpOnly' => true,
        'secure' => true
    ];
}

return $config;
