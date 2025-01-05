<?php

/**
 * Yii bootstrap
 *
 * Нужен для автокомплита компонентов приложения в IDE
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication экземпляр прилодения
     */
    public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = include(__DIR__ . '/../../vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container;

/**
 * @property \yii\queue\amqp_interop\Queue $mailnotifyQueue
 * @property \quartz\settingsModule\components\SettingsComponent $settings
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * @property \yii\web\Response|\yii\console\Response $response Компонент ответа приложения.
 */
class WebApplication extends yii\web\Application
{
}

/**
 */
class ConsoleApplication extends yii\console\Application
{
}
