<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config = [
    'id' => $_ENV['YII_PRODUCT_SETTINGS']['common']['siteID'] . "-backend",
    'name' => $_ENV['YII_PRODUCT_SETTINGS']['common']['siteName'],
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'homeUrl' => '/admin',
    'bootstrap' => [
        'log',
        'quartz\user\bootstrap\UserRoutesBackendBootstrap',
    ],
    'components' => [
        'user' => [
            'identityClass' => common\models\User::class,
            'identityCookie' => [
                'name' => '_identity_backend',
            ],
        ],
        'session' => [
            'name' => 'BACKENDSESSID',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views/layouts' => '@vendor/quartz/yii2-adminlte-theme/views/layouts',
                ],
            ],
            'params' => [
                'left-menu-items' => __DIR__ . '/left-menu-items.php',
                'cacheMenu' => true
            ]
        ],
        'urlManager' => [
            'baseUrl' => '/admin',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
        ],
        'request' => [
            'baseUrl' => '/admin',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'enableCsrfCookie' => true,
            'cookieValidationKey' => $_ENV['YII_PRODUCT_SETTINGS']['common']['backendCookieValidationKey'],
            'hostInfo' => $params['domain'],
            'csrfParam' => '_csrf_backend'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'class' => 'quartz\tools\modules\errorHandler\components\web\ErrorHandler',
            'errorAction' => 'site/error',
        ],
    ],
    'modules' => [
        'adminlteTheme' => [
            'class' => 'quartz\adminlteTheme\AdminlteTheme',
            'externalMessageSource' => true,
            'refDoc' => 'https://docs.google.com/document/d/1uPYqYjP-VeWtwU7T1BGn069JuGdEfvqBiFRzOIeiUu0'
        ],
        'user' => [
            'modelViews' => [
                'b_create' => '@backend/views/user/create',
                'b_update' => '@backend/views/user/update',
            ],
        ],
    ],
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['login', 'error', 'auth2'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'actions' => ['logout'],
                'roles' => ['@'],
            ],
            [
                'allow' => true,
                'roles' => ['backend'],
            ],
        ],
    ],
    'params' => $params,
];

if ($_ENV['YII_PRODUCT_SETTINGS']['common']['useHttps']) {
    $config['components']['request']['csrfCookie'] = ['httpOnly' => true, 'secure' => true];
}

return $config;
