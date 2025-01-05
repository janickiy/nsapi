<?php

namespace api\forms\auth;

use api\exceptions\UserException;
use common\helpers\ApplicationHelper;
use quartz\tools\validators\StripInjectionValidator;
use quartz\user\models\user\User;
use Yii;
use yii\base\Model;
use yii\caching\TagDependency;

/**
 * Login form
 */
class LoginForm extends Model
{
    const
        TIME_UNLOCK_REQUEST_KEY = 'time-unlock-request-key_',
        IP_REQUEST_KEY = 'bad_try_input_ip_',
        INVALIDATE_IP_TAG = 'invalidate-auth-ip-tag_';

    /** @var string|null */
    public ?string $email = null;

    /** @var string|null */
    public ?string $password = null;


    /** @var User|null */
    private ?User $_user = null;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'filter', 'filter' => 'trim'],
            [['email', 'password'], 'filter', 'filter' => 'strip_tags'],
            [['email', 'password'], StripInjectionValidator::class],
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [
                'email', 'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['email' => 'lower(email)'],
            ],
            [['password'], 'string', 'max' => 30,],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Валидация логина/пароля
     *
     * @param string $attribute
     * @return void
     * @throws UserException
     */
    public function validatePassword(string $attribute): void
    {
        $user = $this->getUser();
        $availableAttempt = Yii::$app->params['availableCountAttemptAuth'];

        $ip = Yii::$app->request->getUserIP();
        $keyIp = self::IP_REQUEST_KEY . $ip;
        $invalidateTag = self::INVALIDATE_IP_TAG . $ip;
        $timeUnlockRequestKey = self::TIME_UNLOCK_REQUEST_KEY . $ip;

        $bad_try_input_ip = Yii::$app->cache->get($keyIp);

        $currentTime = time();
        $timeUnlock = Yii::$app->cache->get($timeUnlockRequestKey);
        if (!$timeUnlock || $timeUnlock < $currentTime) {
            if (!$user || !$user->validatePassword($this->password)) {
                /* счетчик неудачных попыток входа по IP */
                $bad_try_input_ip = $bad_try_input_ip ? ++$bad_try_input_ip : 1;
                ApplicationHelper::setCache(
                    $keyIp,
                    $bad_try_input_ip,
                    $invalidateTag
                );

                if ($bad_try_input_ip % $availableAttempt == 0) {
                    $minutesUnlock = pow(2, ($bad_try_input_ip / $availableAttempt) - 1);
                    $timeUnlock = $currentTime + 60 * $minutesUnlock;

                    ApplicationHelper::setCache(
                        $timeUnlockRequestKey,
                        $timeUnlock,
                        $invalidateTag
                    );

                    throw new UserException(
                        sprintf(
                            "Совершено слишком много попыток! Вы заблокированы на %d мин",
                            ceil(($timeUnlock - $currentTime) / 60)
                        )
                    );
                }

                $this->addError(
                    $attribute,
                    Yii::t('auth', 'wrong_auth.Incorrect phone number or password')
                );
            } else {
                TagDependency::invalidate(Yii::$app->cache, $invalidateTag);
            }
        } else {
            throw new UserException(
                sprintf(
                    "Вы заблокированы на %d мин",
                    ceil(($timeUnlock - $currentTime) / 60)
                )
            );
        }
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if (!$this->_user) {
            $this->_user = User::findByEmail($this->email);
        }
        return $this->_user;
    }

    /**
     * @return bool
     */
    public function getRememberMe(): bool
    {
        return false;
    }
}
