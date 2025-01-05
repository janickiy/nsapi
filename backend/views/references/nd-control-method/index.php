<?php

use backend\models\references\NdControlMethodSearch;
use common\models\references\ControlMethod;
use common\models\references\NdControlMethod;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridViewInterface;
use quartz\adminlteTheme\config\AnminLteThemeConfig;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var NdControlMethodSearch $searchModel
 */

$this->title = 'НД на контроль';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$columns = [
    ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
    [
        'attribute' => 'control_method_id',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'filterType' => GridViewInterface::FILTER_SELECT2,
        'filter' => ControlMethod::getAllToList(),
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'filterInputOptions' => ['placeholder' => '---'],
        'value' => function (NdControlMethod $model) {
            return $model->controlMethod ? $model->controlMethod->name : '';
        }
    ],
    [
        'attribute' => 'name',
        'options' => ['style' => 'border-left: 2px solid #ddd;border-right: 2px solid #ddd;'],
        'value' => function (NdControlMethod $model) {
            return $model->name ?? '';
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
    'options' => ['id' => 'dynagrid-nd-control-method'],
];

if (class_exists('\quartz\adminlteTheme\config\AnminLteThemeConfig')) {
    DynaGrid::begin(ArrayHelper::merge(AnminLteThemeConfig::getDefaultConfigDynagrid(), $dynaGridOptions));
} else {
    DynaGrid::begin($dynaGridOptions);
}
DynaGrid::end();
?>
