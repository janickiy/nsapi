<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\user\UserFixture;

class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . '/user/user.php',
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        \Yii::$app->cache->flush();
        $I->amOnPage('/admin');
    }

    /**
     * Пустые поля
     * @param FunctionalTester $I
     *
     * @skip
     */
    public function checkEmpty(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('', ''));
        $I->seeValidationError('Поле не может быть пустым');
        $I->seeValidationError('Поле не может быть пустым');
    }

    /**
     * Не правильный логин и пароль
     * @param FunctionalTester $I
     *
     * @skip
     */
    public function checkWrongPassword(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('admin', 'wrongPassword'));
        $I->seeValidationError('Неверный email');
        $I->seeValidationError('Неверный email пользователя или пароль.');
    }

    /**
     * Корректный логин и пароль
     * @param FunctionalTester $I
     *
     * @skip
     */
    public function checkValidLogin(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('new_user@freematiq.com', 'password'));
        $I->amOnRoute('/admin');
        $I->dontSeeElement('#login-form');
    }

    /**
     * Метод отдачи поелй формы для отправки
     * @param $login
     * @param $password
     * @return array
     */
    protected function formParams($login, $password)
    {
        return [
            'SigninForm[email]' => $login,
            'SigninForm[password]' => $password,
        ];
    }
}
