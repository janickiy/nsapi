<?php

namespace common\tests\unit\models;

use quartz\user\models\forms\SigninForm;
use common\fixtures\user\UserFixture;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /**
     * Сброс кеша перед выполнением
     */
    public function _before()
    {
        \Yii::$app->cache->flush();
        parent::_before();
    }

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    /**
     * Валидация перед логированием
     */
    public function testLoginNoUser()
    {
        $model = new SigninForm();
        expect('model should not validate before login user', $model->validate())->false();
    }

    /**
     * Валидация перед логированием
     */
    public function testLoginUser()
    {
        $model = new SigninForm();
        $model->load([
            'email' => 'new_user@freematiq.com',
            'password' => 'passw0rd'
        ], '');

        expect('model should validate before login user', $model->validate())->true();
    }
}
