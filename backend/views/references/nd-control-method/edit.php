<?php

use backend\forms\references\NdControlMethodForm;
use common\models\references\ControlMethod;
use common\models\references\NdControlMethod;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model NdControlMethod */
/* @var $modelForm NdControlMethodForm */

$this->title = $model->isNewRecord ? 'Создание' : 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'НД на контроль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-body">
            <div class='row'>
                <div class='col-sm-6'>
                    <?= $form->field($modelForm, 'control_method_id')->widget(Select2::class, [
                        'data' => ControlMethod::getAllToList(),
                        'pluginOptions' => [
                            'multiple' => false,
                            'placeholder' => '---'
                        ],
                    ]); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-6'>
                    <?= $form->field($modelForm, 'name')->textInput(); ?>
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
