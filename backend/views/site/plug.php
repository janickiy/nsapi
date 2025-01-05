<?php

use backend\models\PlugForm;
use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var View $this
 * @var PlugForm $model
 */

$this->title = 'Закрыть/Открыть сайт';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <?php $form = ActiveForm::begin(['id' => 'plug-form']); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'plug_status')->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'plug_interval') ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'plug_end') ?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'plug-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
