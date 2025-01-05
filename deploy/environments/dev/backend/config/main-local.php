<?php

$config = [];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['bootstrap'][] = 'extensionDebug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'panels' => [
            'db' => 'quartz\debug\panels\DbPanel',
            'dumper' => 'quartz\debug\panels\DumperPanel',
        ],
    ];
    $config['modules']['extensionDebug'] = [
        'class' => 'quartz\debug\Debug',
        'dumperType' => 'symfony'
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => 'quartz\gii\generators\model\Generator',
            'crud' => 'quartz\gii\generators\crud\Generator',
            'hflst' => 'quartz\gii\generators\hflst\Generator',
            'modelForm' => 'quartz\gii\generators\modelForm\Generator',
        ],
        'controllerNamespace' => 'quartz\gii\controllers',
    ];
}

return $config;
