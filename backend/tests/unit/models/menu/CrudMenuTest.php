<?php

namespace frontend\tests\unit\models\menu;

use common\fixtures\menu\MenuItemsFixture;
use common\fixtures\menu\MenusFixture;
use quartz\menu\models\Menus;

/**
 * Class CrudMenuTest
 * @package backend\tests\unit\models\menu
 */
class CrudMenuTest extends \Codeception\Test\Unit
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
                'class' => MenusFixture::class,
                'dataFile' => codecept_data_dir() . '/menu/menus.php'
            ],
            'userProfile' => [
                'class' => MenuItemsFixture::class,
                'dataFile' => codecept_data_dir() . '/menu/menuItems.php'
            ],
        ]);
    }

    /**
     * Проверка корректного создания пункта меню
     */
    public function testCreateCorrect()
    {
        $menu = new Menus();
        $menu->name = 'test-create-menu';

        $isExpect = true;
        if (!$menu->save()) {
            $isExpect = false;
        }

        expect($isExpect)->true();
        $this->tester->seeRecord(Menus::class, ['name' => $menu->name]);
    }

    /**
     * Проверка корректного удаления пункта меню
     */
    public function testDeleteCorrect()
    {
        $id = 500;
        $menu = Menus::find()->where(['id' => $id])->one();

        $isExpect = true;
        if (!$menu->delete()) {
            $isExpect = false;
        }

        expect($isExpect)->true();
        $this->tester->dontSeeRecord(Menus::class, ['id' => $id]);
    }

    /**
     * Проверка корректного обновления пункта меню
     */
    public function testUpdateCorrect()
    {
        $id = 500;
        $menu = Menus::find()->where(['id' => $id])->one();

        $menu->name = 'test-edit-menu';

        $isExpect = true;
        if (!$menu->save()) {
            $isExpect = false;
        }

        expect($isExpect)->true();
        $this->tester->seeRecord(Menus::class, ['id' => $id, 'name' => $menu->name]);
    }
}
