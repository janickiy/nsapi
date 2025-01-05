<?php

return [
    'class' => $_ENV['YII_PRODUCT_SETTINGS']['mail']['class'],
    'viewPath' => '@common/mail',
    'transport' => [
        'class' => $_ENV['YII_PRODUCT_SETTINGS']['mail']['transport_class'],
        'host' => $_ENV['YII_PRODUCT_SETTINGS']['mail']['host'],
        'username' => $_ENV['YII_PRODUCT_SETTINGS']['mail']['username'],
        'password' => $_ENV['YII_PRODUCT_SETTINGS']['mail']['password'],
        'port' => $_ENV['YII_PRODUCT_SETTINGS']['mail']['port'],
        'encryption' => $_ENV['YII_PRODUCT_SETTINGS']['mail']['encryption'],
    ],
];
