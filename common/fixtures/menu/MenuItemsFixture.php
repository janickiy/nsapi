<?php
namespace common\fixtures\menu;

use yii\test\ActiveFixture;

/**
 * Class MenuItemsFixture
 * @package common\fixtures\menu
 */
class MenuItemsFixture extends ActiveFixture
{
    public $tableName = 'menu_items';
    public $depends = ['common\fixtures\menu\MenusFixture'];
}
