<?php

$hideHttpException = [
    'yii\web\HttpException:404',
    'yii\web\HttpException:403',
];

return [
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => $hideHttpException,
                    'logFile' => '@app/runtime/logs/hide-error.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/error.log',
                    'levels' => ['error'],
                    'except' => $hideHttpException
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/warning.log',
                    'levels' => ['warning']
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/info.log',
                    'levels' => ['info']
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/user.log',
                    'categories' => ['user'],
                    'levels' => ['error']
                ],
            ],
        ],
        'assetManager' => [
            'forceCopy' => $_ENV['YII_PRODUCT_SETTINGS']['common']['needDevAssetForceCopy']
        ],
    ],
];
