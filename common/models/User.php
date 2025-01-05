<?php

namespace common\models;
use Yii;

class User extends \quartz\user\models\user\User
{
    const ROLE_DEVELOPER = 'developer';
    const ROLE_MODERATOR = 'moderator';

    /**
     * Возвращает одну роль пользователя
     *
     * @return string
     */
    public function getCurrentRole(): string
    {
        if (Yii::$app->authManager->checkAccess($this->id, self::ROLE_ADMIN)) {
            return self::ROLE_ADMIN;
        } elseif (Yii::$app->authManager->checkAccess($this->id, self::ROLE_DEVELOPER)) {
            return self::ROLE_DEVELOPER;
        } elseif (Yii::$app->authManager->checkAccess($this->id, self::ROLE_MODERATOR)) {
            return self::ROLE_MODERATOR;
        }

        return self::ROLE_USER;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->getCurrentRole() === self::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isDeveloper(): bool
    {
        return $this->getCurrentRole() === self::ROLE_DEVELOPER;
    }

    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        return $this->getCurrentRole() === self::ROLE_MODERATOR;
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->getCurrentRole() === self::ROLE_USER;
    }
}
