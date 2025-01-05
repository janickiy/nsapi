<?php

namespace frontend\tests\unit\models\rbac;

use common\fixtures\rbac\AuthAssignmentFixture;
use common\fixtures\rbac\AuthItemChildFixture;
use common\fixtures\rbac\AuthItemFixture;
use common\fixtures\rbac\AuthRuleFixture;
use common\fixtures\user\UserFixture;
use common\fixtures\user\UserProfileFixture;
use quartz\user\models\AuthItem;
use Yii;

/**
 * Class CrudRoleTest
 * @package backend\tests\unit\models\user
 */
class CrudRoleTest extends \Codeception\Test\Unit
{
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;

    /**
     * Загружаем фикстуры
     */
    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . '/user/user.php'
            ],
            'userProfile' => [
                'class' => UserProfileFixture::class,
                'dataFile' => codecept_data_dir() . '/user/userProfile.php'
            ],

            'authRule' => [
                'class' => AuthRuleFixture::class,
                'dataFile' => codecept_data_dir() . '/rbac/authRule.php'
            ],
            'authItem' => [
                'class' => AuthItemFixture::class,
                'dataFile' => codecept_data_dir() . '/rbac/authItem.php'
            ],
            'authAssignment' => [
                'class' => AuthAssignmentFixture::class,
                'dataFile' => codecept_data_dir() . '/rbac/authAssignment.php'
            ],
            'authItemChild' => [
                'class' => AuthItemChildFixture::class,
                'dataFile' => codecept_data_dir() . '/rbac/authItemChild.php'
            ],
        ]);
    }

    /**
     * Проверка корректного создания роли с правами
     */
    public function testCreateRoleCorrect()
    {
        $roleName = 'test_create_role';
        $roleDescription = 'description role';

        $role = Yii::$app->authManager->createRole('test_create_role');
        $role->description = 'description role';
        Yii::$app->authManager->add($role);

        // проверка, создалась ли роль
        $this->tester->seeRecord(AuthItem::class, [
            'name' => $roleName,
            'description' => $roleDescription,
        ]);

        // проверка добавления прав в роль
        foreach (['r_user', 'w_user'] as $permit) {
            $new_permit = Yii::$app->authManager->getPermission($permit);
            Yii::$app->authManager->addChild($role, $new_permit);
        }

        $this->tester->seeInDatabase('auth_item_child', [
            'parent' => $roleName,
            'child' => 'r_user',
        ]);

        $this->tester->seeInDatabase('auth_item_child', [
            'parent' => $roleName,
            'child' => 'w_user',
        ]);
    }

    /**
     * Проверка корректного удаления роли с правами
     */
    public function testDeleteRoleCorrect()
    {
        // проверяем, чтобы никто данные не поменял
        $roleName = 'test_delete_role';
        $role = Yii::$app->authManager->getRole($roleName);

        $this->tester->seeRecord(AuthItem::class, [
            'name' => $roleName,
        ]);

        $this->tester->seeInDatabase('auth_item_child', [
            'parent' => $roleName,
            'child' => 'r_user',
        ]);

        $this->tester->seeInDatabase('auth_item_child', [
            'parent' => $roleName,
            'child' => 'w_user',
        ]);

        // удаляем роль и правами
        Yii::$app->authManager->removeChildren($role);
        Yii::$app->authManager->remove($role);

        $this->tester->cantSeeInDatabase('auth_item_child', [
            'parent' => $roleName,
            'child' => 'r_user',
        ]);

        $this->tester->cantSeeInDatabase('auth_item_child', [
            'parent' => $roleName,
            'child' => 'w_user',
        ]);

        $this->tester->cantSeeRecord(AuthItem::class, [
            'name' => $roleName,
        ]);
    }
}
