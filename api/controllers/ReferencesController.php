<?php

namespace api\controllers;

use api\models\references\ControlMethodSearch;
use api\models\references\ControlResultSearch;
use api\models\references\CustomerSearch;
use api\models\references\HardnessSearch;
use api\models\references\OuterDiameterSearch;
use api\models\references\StandardSearch;
use api\models\references\WallThicknessSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 *
 */
class ReferencesController extends ApiController
{
    /**
     * @return array
     */
    function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'standard-list', 'hardness-list', 'outer-diameter-list', 'customer-list',
                            'control-method-list', 'control-result-list', 'wall-thickness-list',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ]);
    }

    /**
     * Список Стандартов
     *
     * @return ActiveDataProvider
     */
    public function actionStandardList(): ActiveDataProvider
    {
        $searchModel = new StandardSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    /**
     * Список Групп прочности
     *
     * @return ActiveDataProvider
     */
    public function actionHardnessList(): ActiveDataProvider
    {
        $searchModel = new HardnessSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    /**
     * Список внешних диаметров
     *
     * @return ActiveDataProvider
     */
    public function actionOuterDiameterList(): ActiveDataProvider
    {
        $searchModel = new OuterDiameterSearch();
        return $searchModel->search();
    }

    /**
     * Список полкупателей
     *
     * @return ActiveDataProvider
     */
    public function actionCustomerList(): ActiveDataProvider
    {
        $searchModel = new CustomerSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    /**
     * Список методов контроля
     *
     * @return ActiveDataProvider
     */
    public function actionControlMethodList(): ActiveDataProvider
    {
        $searchModel = new ControlMethodSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    /**
     * Список результатов контроля
     *
     * @return ActiveDataProvider
     */
    public function actionControlResultList(): ActiveDataProvider
    {
        $searchModel = new ControlResultSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    /**
     * Список толщин стенки
     *
     * @return ActiveDataProvider
     */
    public function actionWallThicknessList(): ActiveDataProvider
    {
        $searchModel = new WallThicknessSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }
}
