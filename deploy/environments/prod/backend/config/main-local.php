<?php
$config = [];

// todo Может выпилить? Это же прод
if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug']['class'] = 'yii\debug\Module';
    $config['modules']['debug']['allowedIPs'] = $_ENV['YII_PRODUCT_SETTINGS']['common']['dp_ips_backend'];
}

return $config;
