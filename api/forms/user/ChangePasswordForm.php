<?php

namespace api\forms\user;

use quartz\tools\validators\StripInjectionValidator;
use quartz\user\models\user\User;
use yii\base\Model;


class ChangePasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $confirm_password;

    protected User $user;

    /**
     * @param User $user
     * @param $config
     */
    public function __construct(User $user, $config = [])
    {
        $this->user = $user;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['old_password', 'new_password', 'confirm_password'], 'filter', 'filter' => 'trim'],
            [['old_password', 'new_password', 'confirm_password'], 'filter', 'filter' => 'strip_tags'],
            [['old_password', 'new_password', 'confirm_password'], StripInjectionValidator::class],
            [['old_password', 'new_password', 'confirm_password'], 'required'],
            [['old_password', 'new_password', 'confirm_password'], 'string', 'max' => 30,],
            ['old_password', 'validatePassword'],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'new_password',
                'operator' => true,
                'message' => 'Пароли не совпадают'
            ],
        ];
    }

    /**
     * Валидация пароля
     *
     * @param string $attribute
     * @return void
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->user->validatePassword($this->old_password)) {
            $this->addError($attribute, 'Неверный пароль');
        }
    }
}
