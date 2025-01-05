<?php
namespace common\fixtures\rbac;

use yii\test\ActiveFixture;

/**
 * Class AuthItemFixture
 * @package common\fixtures\rbac
 */
class AuthItemFixture extends ActiveFixture
{
    public $tableName = 'auth_item';
    public $depends = ['common\fixtures\rbac\AuthRuleFixture'];
}
