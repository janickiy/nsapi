<?php
$apps = ['api', 'backend', 'common', 'frontend'];
$currentlyPath = exec('pwd') . '/..';

// получаем  данные из settings.js
$settings = file_get_contents($currentlyPath . '/settings.json');
$objSettings = json_decode($settings);
$db = $objSettings->db;

$dbData = [
    'host' => $db->host,
    'test-dbname' => $db->{'test-dbname'},
    'login' => $db->login,
    'password' => $db->password,
];

$dsn = 'pgsql:host=' . $dbData['host'] . ';dbname=' . $dbData['test-dbname'];

 // создаем для каждого приложения свой codeception.yml с данными из settings
foreach ($apps as $app) {
    $appPath = "$currentlyPath/$app";
    if (file_exists($appPath)) {
        $codeception = file_get_contents($appPath . '/codeception.yml.dist');
        $codeception = str_replace('config db dsn', $dsn, $codeception);
        $codeception = str_replace('config db user', $dbData['login'], $codeception);
        $codeception = str_replace('config db password', $dbData['password'], $codeception);
        file_put_contents($appPath . '/codeception.yml', $codeception);

        if ($app === 'api') {
            $codeception = file_get_contents($appPath . '/tests/api.suite.yml.dist');
            $codeception = str_replace('config test-domain', $objSettings->common->{'test-domain'}.'/api', $codeception);
            file_put_contents($appPath . '/tests/api.suite.yml', $codeception);
        }
    }
}
