<?php
require("{$_SERVER["DOCUMENT_ROOT"]}/vendor/quartz/yii2-installer/WebInstallerController.php");

$controller = new WebInstallerController([
    "project_dir" => realpath($_SERVER["DOCUMENT_ROOT"]),
    "product_name" => "CoshKey"
]);
echo $controller->run();
