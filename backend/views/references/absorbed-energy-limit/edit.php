<?php

use backend\forms\references\AbsorbedEnergyLimitForm;
use common\models\references\Standard;
use common\models\references\AbsorbedEnergyLimit;
use common\models\references\WallThickness;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model AbsorbedEnergyLimit */
/* @var $modelForm AbsorbedEnergyLimitForm */

$this->title = $model->isNewRecord ? 'Создание' : 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Поглощенная энергия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-body">
            <div class='row'>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'standard_id')->widget(Select2::class, [
                        'data' => Standard::getAllToList(),
                        'pluginOptions' => [
                            'multiple' => false,
                            'placeholder' => '---'
                        ],
                    ]); ?>
                </div>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'wall_thickness_id')->widget(Select2::class, [
                        'data' => WallThickness::getAllToList(),
                        'pluginOptions' => [
                            'multiple' => false,
                            'placeholder' => '---'
                        ],
                    ]); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'value')->textInput(); ?>
                </div>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'value_average')->textInput(); ?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::a('Отмена', ['close'], [
                'class' => 'btn btn-secondary'
            ]) ?>
            <?= Html::submitButton(
                'Сохранить',
                ['class' => 'btn btn-primary'])
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
