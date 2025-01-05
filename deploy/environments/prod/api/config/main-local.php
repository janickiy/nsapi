<?php

$config = [
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/error/application.log',
                    'levels' => ['error']
                ],

                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/warning.log',
                    'levels' => ['warning']
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/info/application.log',
                    'levels' => ['info']
                ]
            ],
        ],
    ],
];

return $config;
