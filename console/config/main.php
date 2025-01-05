<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
  );

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'localization'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'errorHandler' => [
            'class' => 'quartz\tools\modules\errorHandler\components\console\ErrorHandler',
        ],
        'response' => [
            'class' => 'yii\console\Response',
        ]
    ],
    'params' => $params,
    'modules' => [
        'localization' => [
            'class' => 'quartz\localization\Localization',
            'messagesLocations' => [
                '@root/common/messages',
                '@root/vendor/quartz/yii2-rbac/messages',
                '@root/vendor/quartz/yii2-adminlte-theme/messages',
                '@root/vendor/quartz/yii2-tools/messages',
            ]
        ],
//        'dashboard' => [
//            'class' => 'quartz\dashboard\Dashboard',
//            'externalMessageSource' => true,
//        ],
    ],
];
