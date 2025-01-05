<?php

use yii\db\Migration;

/**
 * Class m240524_023233_setting_access_rights
 */
class m240524_023233_setting_access_rights extends Migration
{
    const ROLES = [
        'admin' => [
            'description' => 'Администратор',
            'perms' => ['backend', 'r_site', 'w_site', 'r_settings', 'w_settings', 'r_user', 'w_user'],
        ],
        'developer' => [
            'description' => 'Редактор справочников',
            'perms' => ['backend', 'r_site'],
        ],
        'moderator' => [
            'description' => 'Директор',
            'perms' => [],
        ],
        'user' => [
            'description' => 'Специалист',
            'perms' => [],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (self::ROLES as $name => $item) {
            $role = Yii::$app->authManager->getRole($name);
            if ($role) {
                $role->description = $item['description'];
                Yii::$app->authManager->update($name, $role);
                Yii::$app->authManager->removeChildren($role);
                foreach ($item['perms'] as $permission) {
                    $permission = Yii::$app->authManager->getPermission($permission);
                    if ($permission) {
                        Yii::$app->authManager->addChild($role, $permission);
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
