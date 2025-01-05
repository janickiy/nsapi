<?php

return [
    'id' => 'app-backend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'request' => [
            'hostInfo' => $_ENV['YII_PRODUCT_SETTINGS']['common']['test-domain'],
        ],
    ],
];
