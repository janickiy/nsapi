<?php

return [
    'cors' => [
        'Origin' => $_ENV['YII_PRODUCT_SETTINGS']['common']['accessControlAllowOrigin'] ?? [],
        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        'Access-Control-Request-Headers' => ['*'],
        'Access-Control-Allow-Credentials' => null,
        'Access-Control-Max-Age' => 1,
        'Access-Control-Expose-Headers' => [
            'X-Pagination-Total-Count',
            'X-Pagination-Page-Count',
            'X-Pagination-Current-Page',
            'X-Pagination-Per-Page'
        ],
    ],

    'availableCountAttemptAuth' => 5,
];
