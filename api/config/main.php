<?php

use api\exceptions\ApiException;
use api\exceptions\UnAuthorizeException;
use api\filters\ApiFormatFilter;
use api\forms\auth\LoginForm;
use yii\web\Response;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'base-app',
    'name' => 'base-app.local',
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'homeUrl' => '/api',
    'controllerMap' => [
        // Перекрываем контроллеы модулей
        'user' => 'api\controllers\UserController',
    ],
    'components' => [
        'urlManager' => [
            'baseUrl' => '/api',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'request' => [
            'baseUrl' => '/api',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'enableCsrfCookie' => true,
            'cookieValidationKey' => $_ENV['YII_PRODUCT_SETTINGS']['common']['apiCookieValidationKey'],
            'csrfParam' => '_csrf_api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                '*' => 'yii\web\JsonParser'
            ],
            'hostInfo' => $params['domain'],
        ],
        'response' => [
            'class' => \api\components\Response::class,
            'format' => Response::FORMAT_JSON
        ],
        'user' => [
            'class' => quartz\user\components\web\User::class,
            'identityClass' => common\models\User::class,
            'identityCookie' => [
                'name' => '_identity_api',
            ],
            'enableAutoLogin' => true,
            'loginUrl' => null,
            'enableSession' => true
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
            'class' => 'api\components\ErrorHandler',
        ],
//        'session' => [
//            'cookieParams' => [
//                'sameSite' => 'None'
//            ]
//        ],
    ],

    'modules' => [
        'user' => [
            'modelClasses' => [
                'UserSigninForm' => LoginForm::class,
            ],
        ],
    ],

    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['signin'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function ($rule, $action) {
            throw new UnAuthorizeException();
        }
    ],

    'on beforeRequest' => function () {
        ApiFormatFilter::setAllowOrigin();
    },
    'params' => $params,
];
