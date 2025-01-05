<?php

use yii\web\View;
use yii\helpers\Html;

/**
 * @var View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

if (!Yii::$app->user->can('backend')) {
    /** @phpstan-ignore-next-line */
    $this->context->layout = 'error';
}
$this->title = $name;
?>
<div class="site-error">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <h1><?= Html::encode($this->title) ?></h1>
            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>
            <p>
                <?= Yii::t('yii', 'The above error occurred while the Web server was processing your request.') ?>
            </p>
            <p>
                <?= Yii::t('yii', 'Please contact us if you think this is a server error. Thank you.') ?>
            </p>
        </div>
        <div class="col-sm-3"></div>
    </div>
</div>