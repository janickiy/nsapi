<?php

namespace frontend\tests\unit\models\user;

use common\fixtures\user\UserFixture;
use common\fixtures\user\UserProfileFixture;
use quartz\user\models\forms\SigninForm;
use quartz\user\services\AuthService;

/**
 * Class AuthUserTest
 * @package frontend\tests\unit\models\user
 */
class AuthUserTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
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
        ]);
    }


    /**
     * Авторизация админа под несуществующий емейлом
     */
    public function testAuthInvalidEmail()
    {
        $modelLogin = new SigninForm();
        $modelLogin->email = 'admin@free.com';
        $modelLogin->password = 'passw0rd';

        $authService = new AuthService();

        $expect = false;
        try {
            $authService->signin($modelLogin->attributes);
        } catch (\Exception $e) {
            $expect = true;
        }

        expect($expect)->true();
    }

    /**
     * Авторизация админа с неверным паролем
     */
    public function testAuthInvalidPassword()
    {
        $modelLogin = new SigninForm();
        $modelLogin->email = 'adminfmq@freematiq.com';
        $modelLogin->password = 'passw0rd7';

        $authService = new AuthService();

        $expect = false;
        try {
            $authService->signin($modelLogin->attributes);
        } catch (\Exception $e) {
            $expect = true;
        }

        expect($expect)->true();
    }

    /**
     * Авторизация админа с пустыми данными
     */
    public function testAuthEmptyData()
    {
        $modelLogin = new SigninForm();
        $modelLogin->email = '';
        $modelLogin->password = '';

        $authService = new AuthService();

        $expect = false;
        try {
            $authService->signin($modelLogin->attributes);
        } catch (\Exception $e) {
            $expect = true;
        }

        expect($expect)->true();
    }

    /**
     * Авторизация админа с правильными данными
     */
    public function testAuthCorrectAuth()
    {
        $modelLogin = new SigninForm();
        $modelLogin->email = 'adminfmq@freematiq.com';
        $modelLogin->password = 'passw0rd';

        $authService = new AuthService();
        $user = $authService->signin($modelLogin->attributes);

        expect(!empty($user))->true();
    }

    /**
     * Проверка авторизациии под забаненым пользователем
     * Авторизация пройти не должна
     */
    public function testAuthBanUser()
    {
        $modelLogin = new SigninForm();
        $modelLogin->email = 'user_ban@freematiq.com';
        $modelLogin->password = 'passw0rd';

        $authService = new AuthService();

        $expect = false;
        try {
            $authService->signin($modelLogin->attributes);
        } catch (\Exception $e) {
            $expect = true;
        }

        expect($expect)->true();
    }

    /**
     * Авторизация под пользователем, который ещё не идентифицировал почтовый ящик
     * Авторизация пройти не должна
     */
    public function testAuthUserNodIdentifyEmail()
    {
        $modelLogin = new SigninForm();
        $modelLogin->email = 'user_not_identify_email@freematiq.com';
        $modelLogin->password = 'passw0rd';

        $authService = new AuthService();

        $expect = false;
        try {
            $authService->signin($modelLogin->attributes);
        } catch (\Exception $e) {
            $expect = true;
        }

        expect($expect)->true();
    }
}
