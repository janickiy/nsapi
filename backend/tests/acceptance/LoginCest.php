<?php
namespace backend\tests\acceptance;

use backend\tests\AcceptanceTester;
use common\fixtures\user\UserFixture;

/**
 * Class LoginCest
 * @package backend\tests\acceptance
 */
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
     * @skip
     */
    public function checkHome(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->maximizeWindow();
        $I->waitForElement('#login-form');
        $I->see('Your-site-name');

        $I->fillField(['name' => 'SigninForm[email]'], 'adminfmq@freematiq.com');
        $I->fillField(['name' => 'SigninForm[password]'], 'passw0rd');
        $I->click('button[type="submit"]');
        $I->waitForElement('a[href="/admin/logout"]');
        $I->see('Админка');
    }
}
