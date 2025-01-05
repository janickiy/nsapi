<?php

namespace api\tests\api;

use api\tests\ApiTester;
use common\fixtures\user\UserFixture;
use Codeception\Util\HttpCode;

/**
 * Class IndexCest
 * api/tests/api/IndexCest.php
 * @package api\tests\api
 */
class IndexCest
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
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ];
    }

    /**
     * Авторизация по API
     *
     * @param ApiTester $I
     *
     */
    public function signin(ApiTester $I)
    {
        $I->wantTo('Получение списока всех записей.');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Client-Id', 'testclient');
        $I->haveHttpHeader('Client-Secret', 'testpass');
        $I->sendPOST('/auth/signin', [
            'email' => 'adminfmq@freematiq.com',
            'password' => 'passw0rd'
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.user.access_token');
        $I->seeResponseJsonMatchesJsonPath('$.user.access_expires');
        $I->seeResponseJsonMatchesJsonPath('$.user.refresh_token');
        $I->seeResponseJsonMatchesJsonPath('$.user.refresh_expires');
    }
}
