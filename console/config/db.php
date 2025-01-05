<?php

return [
    'class' => $_ENV['YII_PRODUCT_SETTINGS']['db']['class'],
    'dsn' =>
        'pgsql:host='.$_ENV['YII_PRODUCT_SETTINGS']['db']['host'].
        ';dbname='.$_ENV['YII_PRODUCT_SETTINGS']['db']['dbname'],
    'username' => $_ENV['YII_PRODUCT_SETTINGS']['db']['login'],
    'password' => $_ENV['YII_PRODUCT_SETTINGS']['db']['password'],
    'charset' => $_ENV['YII_PRODUCT_SETTINGS']['db']['charset'],
    'schemaCache' => $_ENV['YII_PRODUCT_SETTINGS']['db']['schemaCache']
];
