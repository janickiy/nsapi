<?php
return [
    'adminEmail' => $_ENV['YII_PRODUCT_SETTINGS']['common']['adminEmail'],
    'supportEmail' => $_ENV['YII_PRODUCT_SETTINGS']['common']['supportEmail'],
    'user.passwordResetTokenExpire' => 3600,

    'languages'  =>  [
        'ru' => 'Русский',
        'en' => 'English',
    ],
    'defaultLanguage' => 'ru',
    'localization_dir' => $_ENV['YII_PRODUCT_SETTINGS']['common']['localization_dir'],

    'reCapchaSecret' => $_ENV['YII_PRODUCT_SETTINGS']['capcha']['reCapchaSecret'],
    'reCapchaSiteKey' => $_ENV['YII_PRODUCT_SETTINGS']['capcha']['reCapchaSiteKey'],

    'uploadTempDir' => '@root/upload/temp',
    'domain' => $_ENV['YII_PRODUCT_SETTINGS']['common']['domain'],
    'test-domain' => $_ENV['YII_PRODUCT_SETTINGS']['common']['test-domain'],
    'bsVersion' => 4,
    'cloudflare' => $_ENV['YII_PRODUCT_SETTINGS']['cloudflare'],
];
