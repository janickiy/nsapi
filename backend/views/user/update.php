<?php
/**
 * @var \yii\web\View $this
 * @var \quartz\user\forms\user\UserForm $userForm
 * @var \quartz\user\forms\user\UserRoleForm $roleForm
 */

use yii\bootstrap4\Tabs;

$this->title = \Yii::t('auth', 'Update user');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('auth', 'users'), 'url' => ['/user']];
$this->params['breadcrumbs'][] = $this->title;

$items[] = [
    'label' => \Yii::t('auth', 'Create_user.tab_title.profile'),
    'content' => $this->render('_formProfile', ['userForm' => $userForm]),
];

if (!$userForm->isNewRecord) {
    $items[] = [
        'label' => 'Роль',
        'content' => $this->render('_formRoles', ['roleForm' => $roleForm]),
        'active' => $roleForm->isNewRecord
    ];
}

echo Tabs::widget([
    'items' => $items,
    'clientOptions' => ['collapsible' => false],
]);
