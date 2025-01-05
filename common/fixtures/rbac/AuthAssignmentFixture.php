<?php
namespace common\fixtures\rbac;

use yii\test\ActiveFixture;

/**
 * Class AuthAssignmentFixture
 * @package common\fixtures\rbac
 */
class AuthAssignmentFixture extends ActiveFixture
{
    public $tableName = 'auth_assignment';
    public $depends = ['common\fixtures\rbac\AuthItemFixture'];
}
