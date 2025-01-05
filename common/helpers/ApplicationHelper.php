<?php

namespace common\helpers;

use quartz\settingsModule\components\SettingsComponent;
use Yii;
use yii\caching\TagDependency;
use yii\i18n\Formatter;

/**
 * Класс ApplicationHelper
 * @package common\helpers
 */
class ApplicationHelper
{
    /** @var ApplicationHelper */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return ApplicationHelper
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Банковское округление
     *
     * @param float $value Округляемое число
     * @param int $precision Количество знаков после запятой у результата
     * @return float
     */
    public static function roundBank(float $value, int $precision = 2): float
    {
        $pow = pow(10, $precision + 1);

        $n3 = ($value * $pow) % 10;
        $n = (int)($value * $pow) / $pow;

        if ($n3 == 5 && abs($n - $value) < 0.0000000001) {
            return round(floor($value * $pow) / $pow, $precision, PHP_ROUND_HALF_EVEN);
        }

        return round($value, $precision);
    }

    /**
     * Возвращает форматтер
     *
     * @return Formatter
     */
    public static function getFormatter()
    {
        return Yii::$app->formatter;
    }

    /**
     * @return SettingsComponent
     */
    public static function getSettingsComponent()
    {
        /** @var SettingsComponent $component */
        $component = Yii::$app->getModule('settings')->settingsComponent; // @phpstan-ignore-line
        return $component;
    }

    /**
     * @param mixed $cacheKey
     * @param mixed $value
     * @param string|string[] $tags
     * @param int $duration
     * @return bool
     */
    public static function setCache($cacheKey, $value, $tags = [], int $duration = 0)
    {
        return Yii::$app->cache->set(
            $cacheKey,
            $value,
            $duration,
            new TagDependency(['tags' => $tags])
        );
    }
}
