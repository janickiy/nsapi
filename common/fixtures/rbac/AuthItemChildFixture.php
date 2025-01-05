<?php
namespace common\fixtures\rbac;

use yii\test\ActiveFixture;

/**
 * Class AuthItemChildFixture
 * @package common\fixtures\rbac
 */
class AuthItemChildFixture extends ActiveFixture
{
    public $tableName = 'auth_item_child';
    public $depends = ['common\fixtures\rbac\AuthItemFixture'];
}
