<?php

use backend\models\references\MassFractionSearch;
use common\models\references\Hardness;
use common\models\references\Standard;
use common\models\references\MassFraction;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridViewInterface;
use quartz\adminlteTheme\config\AnminLteThemeConfig;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var MassFractionSearch $searchModel
 */

$this->title = 'Массовая доля элементов';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$columns = [
    ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
    [
        'attribute' => 'hardness_id',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'filterType' => GridViewInterface::FILTER_SELECT2,
        'filter' => Hardness::getAllToList(),
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'filterInputOptions' => ['placeholder' => '---'],
        'value' => function (MassFraction $model) {
            return $model->hardness ? $model->hardness->name : '';
        }
    ],
    [
        'attribute' => 'carbon',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'value' => function (MassFraction $model) {
            return $model->carbon ? Yii::$app->formatter->asDecimal($model->carbon, 3) : '';
        }
    ],
    [
        'attribute' => 'manganese',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'value' => function (MassFraction $model) {
            return $model->manganese ? Yii::$app->formatter->asDecimal($model->manganese, 3) : '';
        }
    ],
    [
        'attribute' => 'silicon',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'value' => function (MassFraction $model) {
            return $model->silicon ? Yii::$app->formatter->asDecimal($model->silicon, 3) : '';
        }
    ],
    [
        'attribute' => 'sulfur',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'value' => function (MassFraction $model) {
            return $model->sulfur ? Yii::$app->formatter->asDecimal($model->sulfur, 3) : '';
        }
    ],
    [
        'attribute' => 'phosphorus',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'value' => function (MassFraction $model) {
            return $model->phosphorus ? Yii::$app->formatter->asDecimal($model->phosphorus, 3) : '';
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update}',
        'buttons' => [
            'update' => function ($url) {
                return Html::a('<span class="icon fa fa-edit"></span>', $url, [
                    'class' => 'btn btn-sm btn-success',
                    'title' => 'Редактировать',
                ]);
            },
            'delete' => function ($url) {
                return Html::a('<span class="icon fa fa-trash"></span>', $url, [
                    'class' => 'btn btn-sm btn-danger',
                    'title' => 'Удалить',
                    'data-confirm' => 'Вы уверены?'
                ]);
            },
        ],
        'options' => ['style' => 'width:130px;']
    ],
];

$dynaGridOptions = [
    'columns' => $columns,
    'gridOptions' => [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'before' => Html::a('<i class="btn-label glyphicon fa fa-plus"></i> &nbsp&nbsp' . 'Создать', ['create'], ['class' => 'btn btn-labeled btn-success no-margin-t']),
            'after' => '<div class="pull-right">{pager}</div>',
        ],
    ],
    'options' => ['id' => 'dynagrid-mass-fraction'],
];

if (class_exists('\quartz\adminlteTheme\config\AnminLteThemeConfig')) {
    DynaGrid::begin(ArrayHelper::merge(AnminLteThemeConfig::getDefaultConfigDynagrid(), $dynaGridOptions));
} else {
    DynaGrid::begin($dynaGridOptions);
}
DynaGrid::end();
?>
