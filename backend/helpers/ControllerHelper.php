<?php

namespace backend\helpers;

use yii\helpers\Url;

class ControllerHelper
{
    /**
     * Запоминает $url с прфиксом $prefix
     * @param $prefix
     * @param $url
     */
    public static function setStoredUrl($prefix, $url){
        Url::remember(Url::current(),$prefix.'_'.$url);
    }

    /**
     * Возвращает запомненный $url с префиксом $prefix
     *(для запоминания параметров поиска в динагриде)
     *
     * @param $prefix
     * @param $url
     * @return array|string
     */
    public static function getStoredUrl($prefix, $url){
        $p_url = Url::previous($prefix.'_'.$url);
        return $p_url ?? [$url];
    }
}