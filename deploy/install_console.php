<?php

$params = [
    "compress::", //Массив приложений для сжатия css, js
    "clear_settings", //Переконфигурировать все настройки
    "no_migrate", //Не применять миграции
];

require("../vendor/quartz/yii2-installer/ConsoleInstallerController.php");

$controller = new ConsoleInstallerController([
    "project_dir" => realpath("..")
]);
echo $controller->run(getopt('', $params));
