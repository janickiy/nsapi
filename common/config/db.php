<?php

return [
    'class' => $_ENV['YII_PRODUCT_SETTINGS']['db']['class'],
    'dsn' =>
        'pgsql:host=' . $_ENV['YII_PRODUCT_SETTINGS']['db']['host'] .
        ';dbname=' . $_ENV['YII_PRODUCT_SETTINGS']['db']['dbname'],
    'username' => $_ENV['YII_PRODUCT_SETTINGS']['db']['login'],
    'password' => $_ENV['YII_PRODUCT_SETTINGS']['db']['password'],
    'charset' => $_ENV['YII_PRODUCT_SETTINGS']['db']['charset'],
    'schemaCache' => $_ENV['YII_PRODUCT_SETTINGS']['db']['schemaCache'],
    'on afterOpen' => function ($event) {
        $tz = date_default_timezone_get();  // Получаем текущую временную зону
        Yii::$app->db->createCommand("SET TIME ZONE '$tz'")->execute(); // Устанавливаем зону в БД
        /** @var \yii\db\Connection $db */
        if ($db = $event->sender) {
            $db->getSchema()->exceptionMap['SQLSTATE[22003'] = \common\exceptions\db\NumericValueOutOfRange::class;
        }
        return true;
    },
];
