<?php

namespace backend\tests\unit\models;

use common\fixtures\user\UserFixture as UserFixture;
use quartz\user\models\forms\SigninForm;

/**
 * Class SignInFormTest
 * @package backend\tests\unit\models
 */
class SignInFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        \Yii::$app->cache->flush();
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . '/user/user.php'
            ]
        ]);
    }

    public function testValidateWithWrongData()
    {
        $model = new SigninForm();
        $model->email = 'not-existing-email@example.com';
        $model->password = 'not-existing-email@example.com';
        expect($model->validate())->false();
    }

    public function testValidateSuccessData()
    {
        $model = new SigninForm();
        $model->email = 'adminfmq@freematiq.com';
        $model->password = 'passw0rd';
        expect($model->validate())->true();
    }
}
