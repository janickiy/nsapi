<?php
/**
 * @var \yii\web\View $this
 * @var \quartz\user\forms\user\UserForm $userForm
 */

use yii\bootstrap4\Tabs;

$this->title = Yii::t('auth', 'Create user');
$this->params['breadcrumbs'][] = ['label' => Yii::t('auth', 'users'), 'url' => ['/user']];
$this->params['breadcrumbs'][] = $this->title;

$items[] = [
    'label' => Yii::t('auth', 'Create_user.tab_title.profile'),
    'content' => $this->render('_formProfile', ['userForm' => $userForm]),
];

echo Tabs::widget([
    'items' => $items,
    'clientOptions' => ['collapsible' => false],
]);
