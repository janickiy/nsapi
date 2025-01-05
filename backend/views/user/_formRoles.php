<?php
/**
 * @var \quartz\user\forms\user\UserRoleForm $roleForm
 * @var \yii\web\View $this
 * @var \yii\widgets\ActiveForm $form
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-role-update_roles',
    'action' => Url::to(['/user/role-save', 'id' => $roleForm->id_user]),
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($roleForm, 'roles')->widget(Select2::class, [
                    'pluginOptions' => [
                        'multiple' => false,
                        'allowClear' => false,
                        'placeholder' => \Yii::t('auth', 'Form_roles.roles.placeholder'),
                    ],
                    'data' => $roleForm->getRolesDataList()
                ])->label('Роль') ?>
            </div>
            <div class="row hidden">
                <div class="col-sm-6">
                    <?= $form->field($roleForm, 'id_user')->input('hidden')->label(false) ?>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?= Html::submitButton(\Yii::t('auth', 'Form_roles.button_save'), ['class' => 'btn btn-success', 'name' => 'save-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

