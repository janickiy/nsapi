<?php

namespace frontend\tests\unit\models\user;

use common\fixtures\rbac\AuthAssignmentFixture;
use common\fixtures\rbac\AuthItemChildFixture;
use common\fixtures\rbac\AuthItemFixture;
use common\fixtures\rbac\AuthRuleFixture;
use common\fixtures\user\UserFixture;
use common\fixtures\user\UserProfileFixture;
use common\models\User;
use quartz\user\models\UserProfile;
use quartz\user\models\forms\UserFormBackend;
use quartz\user\services\UserAdminService;

/**
 * Class CrudUserTest
 * @package backend\tests\unit\models\user
 */
class CrudUserTest extends \Codeception\Test\Unit
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

            // rbac
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
     * Создание нового пользователя
     */
    public function testCreateNewUser()
    {
        $userForm = new UserFormBackend();

        $userForm->isNewRecord = true;
        $userForm->username = 'test_create';
        $userForm->email = 'test_create@freematiq.com';
        $userForm->password = 'passw0rd';
        $userForm->firstname = 'Алекс';
        $userForm->sex = 1;

        $userService = new UserAdminService(new User());

        $user = $userService->create($userForm->attributes);

        expect(!empty($user))->true();

        $this->tester->seeRecord(User::class, [
            'id' => $user->id,
            'username' => $userForm->username,
            'email' => $userForm->email,
        ]);

        $this->tester->seeRecord(UserProfile::class, [
            'id_user' => $user->id,
            'firstname' => $userForm->firstname,
            'sex' => $userForm->sex,
        ]);
    }

    /**
     * Редактирование пользователя
     */
    public function testEditUser()
    {
        $oldEmail = 'admin_edit@freematiq.com';
        $user = User::findByEmail($oldEmail);
        $oldPasswordHash = $user->password_hash;

        $userForm = new UserFormBackend();
        $userForm->prepareUpdate($user);

        $userForm->isNewRecord = true;
        $userForm->username = 'test_edit';
        $userForm->email = 'test_edit@freematiq.com';
        $userForm->password = 'passw0rd';
        $userForm->firstname = 'Алекс';
        $userForm->middlename = 'test_middlename';
        $userForm->lastname = 'test_lastname';
        $userForm->phone_number = '79293983289';
        $userForm->skype = 'test_skype';
        $userForm->icq = '1234567890';
        $userForm->sex = 0;

        $userService = new UserAdminService($user);

        $user = $userService->update($userForm->attributes, []);

        $isAllDataEdited =
            $user->username == 'test_edit' && $user->email == 'test_edit@freematiq.com' &&
            $user->password_hash != $oldPasswordHash && $user->profile->firstname == 'Алекс' &&
            $user->profile->middlename == 'test_middlename' && $user->profile->lastname == 'test_lastname' &&
            $user->profile->phone_number == '9293983289' && $user->profile->skype == 'test_skype' &&
            $user->profile->icq == '1234567890' && $user->profile->sex == 0;

        expect($isAllDataEdited)->true();

        $this->tester->dontSeeRecord(User::class, [
            'email' => $oldEmail
        ]);

        $this->tester->seeRecord(User::class, [
            'id' => $user->id,
            'username' => $userForm->username,
            'email' => $userForm->email,
        ]);

        $this->tester->seeRecord(UserProfile::class, [
            'id_user' => $user->id,
            'firstname' => $userForm->firstname,
            'sex' => $userForm->sex,
            'middlename' => $userForm->middlename,
            'lastname' => $userForm->lastname,
        ]);
    }

    /**
     * Удаление пользователя
     */
    public function testDeleteUser()
    {
        // авторизация под админом
        \Yii::$app->user->login(User::findOne(500));
        $user = User::findByEmail('admin_edit@freematiq.com');
        $userService = new UserAdminService($user);

        $isExpect = true;
        try {
            $userService->delete();

            if ($user->status != User::STATUS_DELETED) {
                $isExpect = false;
            }
        } catch (\Exception $e) {
            $isExpect = false;
        }

        expect($isExpect)->true();

        $this->tester->seeRecord(User::class, [
            'id' => $user->id,
            'status' => User::STATUS_DELETED
        ]);

        $this->tester->seeRecord(UserProfile::class, [
            'id_user' => $user->id,
        ]);
    }
}
