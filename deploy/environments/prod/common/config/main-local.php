<?php

$hideHttpException = [
    'yii\web\HttpException:404',
    'yii\web\HttpException:403',
];

return [
    'components' => [
        'assetManager' => [
            'forceCopy' => $_ENV['YII_PRODUCT_SETTINGS']['common']['needDevAssetForceCopy']
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/hide-error.log',
                    'levels' => ['error'],
                    'categories' => $hideHttpException,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/error.log',
                    'levels' => ['error'],
                    'except' => $hideHttpException
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/user.log',
                    'categories' => ['user'],
                    'levels' => ['error']
                ],
            ],
        ],
        'db' => [
            'enableSchemaCache' => true
        ],
        'authManager' => [
            'cache' => 'cache'
        ],
    ],
];
