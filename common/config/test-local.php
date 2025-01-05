<?php

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
    [
        'components' => [
            'db' => [
                'dsn' =>
                    'pgsql:host='.$_ENV['YII_PRODUCT_SETTINGS']['db']['host'].
                    ';dbname='.$_ENV['YII_PRODUCT_SETTINGS']['db']['test-dbname'],
            ]
        ],
    ]
);
