<?php
namespace common\fixtures\user;

use yii\test\ActiveFixture;

/**
 * Class UserProfileFixture
 * @package common\fixtures\user
 */
class UserProfileFixture extends ActiveFixture
{
    public $modelClass = 'quartz\user\models\UserProfile';
    public $depends = ['common\fixtures\user\UserFixture'];
}
