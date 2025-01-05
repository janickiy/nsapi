<?php

use backend\forms\references\WallThicknessForm;
use common\models\references\WallThickness;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model WallThickness */
/* @var $modelForm WallThicknessForm */

$this->title = $model->isNewRecord ? 'Создание' : 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Толщина стенки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-body">
            <div class='row'>
                <div class='col-sm-6'>
                    <?= $form->field($modelForm, 'value')->textInput(); ?>
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
