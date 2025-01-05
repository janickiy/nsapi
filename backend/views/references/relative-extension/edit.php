<?php

use backend\forms\references\RelativeExtensionForm;
use common\models\references\Hardness;
use common\models\references\OuterDiameter;
use common\models\references\Standard;
use common\models\references\RelativeExtension;
use common\models\references\WallThickness;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model RelativeExtension */
/* @var $modelForm RelativeExtensionForm */

$this->title = $model->isNewRecord ? 'Создание' : 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Относительное удлинение', 'url' => ['index']];
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
                    <?= $form->field($modelForm, 'hardness_id')->widget(Select2::class, [
                        'data' => Hardness::getAllToList(),
                        'pluginOptions' => [
                            'multiple' => false,
                            'placeholder' => '---'
                        ],
                    ]); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-3'>
                    <?= $form->field($modelForm, 'outer_diameter_id')->widget(Select2::class, [
                        'data' => OuterDiameter::getAllToList(),
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
                <div class='col-sm-6'>
                    <?= $form->field($modelForm, 'value')
                        ->textInput()
                        ->label('Относительное удлинение на 50 мм (2 дюйма для RT), %');
                    ?>
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
