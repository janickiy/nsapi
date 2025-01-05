<?php

use backend\models\references\TheoreticalMassSearch;
use common\models\references\OuterDiameter;
use common\models\references\TheoreticalMass;
use common\models\references\WallThickness;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kartik\grid\GridViewInterface;
use quartz\adminlteTheme\config\AnminLteThemeConfig;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var TheoreticalMassSearch $searchModel
 */

$this->title = 'Теоретическая масса';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$columns = [
    ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
    [
        'attribute' => 'outer_diameter_id',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'filterType' => GridViewInterface::FILTER_SELECT2,
        'filter' => OuterDiameter::getAllToList(),
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'filterInputOptions' => ['placeholder' => '---'],
        'value' => function (TheoreticalMass $model) {
            return $model->outerDiameter
                ? Yii::$app->formatter->asDecimal($model->outerDiameter->millimeter ,2)
                : '';
        }
    ],
    [
        'attribute' => 'wall_thickness_id',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'filterType' => GridViewInterface::FILTER_SELECT2,
        'filter' => WallThickness::getAllToList(),
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'filterInputOptions' => ['placeholder' => '---'],
        'value' => function (TheoreticalMass $model) {
            return $model->wallThickness ? Yii::$app->formatter->asDecimal($model->wallThickness->value, 2) : '';
        }
    ],
    [
        'attribute' => 'value',
        'label' => 'Теоретическая масса 1 метра ГНКТ, кг',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'value' => function (TheoreticalMass $model) {
            return $model->value ? Yii::$app->formatter->asDecimal($model->value, 2) : '';
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
    'options' => ['id' => 'dynagrid-theoretical-mass'],
];

if (class_exists('\quartz\adminlteTheme\config\AnminLteThemeConfig')) {
    DynaGrid::begin(ArrayHelper::merge(AnminLteThemeConfig::getDefaultConfigDynagrid(), $dynaGridOptions));
} else {
    DynaGrid::begin($dynaGridOptions);
}
DynaGrid::end();
?>
