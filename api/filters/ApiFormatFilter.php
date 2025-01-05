<?php

namespace api\filters;

use Yii;
use yii\filters\Cors;

/**
 * Фильтр для API методов
 *
 * Class ApiFormatFilter
 * @package app\filters
 */
class ApiFormatFilter extends Cors
{
    /**
     * @return void
     */
    public static function setAllowOrigin()
    {
        \Yii::$app->response->headers->set('Access-Control-Allow-Origin', self::getAccessControlAllowOrigin());
        \Yii::$app->response->headers->set('Access-Control-Allow-Credentials', 'true');
    }

    /**
     * @return array|mixed|string
     */
    private static function getAccessControlAllowOrigin()
    {
        $originHeader = Yii::$app->getRequest()->headers->get('origin');
        $header = $_ENV['YII_PRODUCT_SETTINGS']['common']['accessControlAllowOrigin'];
        return in_array($originHeader, $header) ? $originHeader : array_shift($header);
    }
}
