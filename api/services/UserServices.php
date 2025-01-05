<?php

namespace api\services;

use api\exceptions\ValidationException;
use api\forms\user\ChangePasswordForm;
use yii\base\Exception;
use common\models\User;

class UserServices
{
    protected User $model;

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Смена пароля пользователя
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     * @throws Exception
     */
    public function changePassword(array $data): void
    {
        $form = new ChangePasswordForm($this->model);
        $form->attributes = $data;
        if (!$form->validate()) {
            throw new ValidationException($form->getFirstErrors());
        }

        $this->model->setPassword($form->new_password);
        if (!$this->model->save()) {
            throw new ValidationException($this->model->getFirstErrors());
        }
    }
}
