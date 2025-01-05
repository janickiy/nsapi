<?php

use backend\forms\references\MassFractionForm;
use common\models\references\Hardness;
use common\models\references\MassFraction;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model MassFraction */
/* @var $modelForm MassFractionForm */

$this->title = $model->isNewRecord ? 'Создание' : 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Массовая доля элементов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-body">
            <div class='row'>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'hardness_id')->widget(Select2::class, [
                        'data' => Hardness::getAllToList(),
                        'pluginOptions' => [
                            'multiple' => false,
                            'placeholder' => '---'
                        ],
                    ]); ?>
                </div>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'carbon')->textInput(); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'manganese')->textInput(); ?>
                </div>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'silicon')->textInput(); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'sulfur')->textInput(); ?>
                </div>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'phosphorus')->textInput(); ?>
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
