<?php

namespace api\controllers;

use api\exceptions\UserException;
use quartz\tools\modules\errorHandler\exceptions\Exception;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;
use quartz\user\services\user\UserAuthService;
use Yii;
use yii\filters\AccessControl;

class AuthController extends ApiController
{
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'signin',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'logout',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ]);
    }

    /**
     * Авторизует пользователя по почте и паролю
     *
     * @throws Exception
     * @throws UserException
     */
    public function actionSignin(): void
    {
        try {
            (new UserAuthService())->signin(Yii::$app->request->post());
        } catch (ValidationException $e) {
            throw new UserException("Неверный логин или пароль");
        }
    }

    /**
     * @return void
     */
    public function actionLogout(): void
    {
        (new UserAuthService())->logout();
    }
}
