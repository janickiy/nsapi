<?php

namespace api\controllers;

use api\exceptions\ValidationException;
use api\services\UserServices;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;

/**
 * Class UserController
 * @package api\controllers
 */
class UserController extends ApiController
{
    /**
     * @return array
     */
    function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['info', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ]);
    }

    /**
     * @return array
     */
    public function actionInfo(): array
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        return [
            'id' => $user->id,
            'email' => $user->email,
            'last_name' => $user->profile?->lastname,
            'first_name' => $user->profile?->firstname,
            'middle_name' => $user->profile?->middlename,
            'role' =>   $user->getCurrentRole(),
        ];
    }

    /**
     * Смена пароля пользователя
     *
     * @return void
     * @throws ValidationException
     * @throws Exception
     */
    public function actionChangePassword(): void
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $service = new UserServices($user);
        $service->changePassword(Yii::$app->request->post());
    }
}
