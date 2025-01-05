<?php

use backend\forms\references\OuterDiameterForm;
use common\models\references\OuterDiameter;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model OuterDiameter */
/* @var $modelForm OuterDiameterForm */

$this->title = $model->isNewRecord ? 'Создание' : 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Наружний диаметр ГНКТ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-body">
            <div class='row'>
                <div class='col-sm-6'>
                    <?= $form->field($modelForm, 'millimeter')->textInput(); ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class='row'>
                <div class='col-sm-6'>
                    <?= $form->field($modelForm, 'inch')->textInput(); ?>
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
