<?php
/**
 * @var \quartz\user\forms\user\UserForm $userForm
 * @var \yii\web\View $this
 * @var \yii\widgets\ActiveForm $form
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

\quartz\user\assets\UserAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-role-update',
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6"><?= $form->field($userForm, 'email') ?></div>
            <div class="col-sm-6"><?= $form->field($userForm, 'password')->passwordInput() ?></div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?php
                // Двухфакторная авторизация
                if ($userForm->isUseTwoFactor()) {
                    echo $form->field($userForm, 'twoFactor')->dropDownList($userForm->getTwoFactorDataList(), [
                        'prompt' => \Yii::t('auth', 'Form_profile.two_factor.default'),
                    ]);
                }
                ?>
            </div>
            <div class="col-sm-6"></div>
        </div>

        <div class="row">
            <div class="col-sm-6"><?= $form->field($userForm, 'lastname')->textInput(['maxlength' => 100]) ?></div>

            <div class="col-sm-6"><?= $form->field($userForm, 'firstname')->textInput(['maxlength' => 100]) ?></div>
        </div>

        <div class="row">
            <div class="col-sm-6"><?= $form->field($userForm, 'middlename')->textInput(['maxlength' => 100]) ?></div>
            <div class="col-sm-6">
                <?= $form->field($userForm, 'phone_number')->textInput([
                    'id' => 'js-phone-user',
                ])->label($userForm->getAttributeLabel('phone_number')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12"><?= $form->field($userForm, 'notes')->textarea(['rows' => 6]) ?></div>
        </div>
    </div>

    <div class="card-footer">
        <?= Html::submitButton(\Yii::t('auth', 'Form_profile.button_save'), ['class' => 'btn btn-success', 'name' => 'save-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

