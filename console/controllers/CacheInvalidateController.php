<?php

namespace console\controllers;

use yii\caching\TagDependency;
use yii\console\Controller;

/**
 * Class CacheController
 * @package console\controllers
 */
class CacheInvalidateController extends Controller
{
    /**
     * Сброс кеша по тегу
     * @param string $tagName
     * @return void
     */
    public function actionIndex($tagName)
    {
        TagDependency::invalidate(\Yii::$app->cache, $tagName);
    }
}
