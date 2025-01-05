<?php

namespace api\controllers;

use api\filters\ApiFormatFilter;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Базовый контроллер для API контроллеров
 *
 * @package app\controllers
 */
class ApiController extends Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'formatter' =>  [
                'class' => ApiFormatFilter::class,
                'cors' => Yii::$app->params['cors'] ?? [],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }
}
