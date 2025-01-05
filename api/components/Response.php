<?php

namespace api\components;

use yii\base\Event;
use yii\web\Cookie;

class Response extends \yii\web\Response
{
    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->on(static::EVENT_BEFORE_SEND, [$this, 'beforeSend']);
    }

    /**
     * @param Event $event
     * @return void
     */
    public function beforeSend(Event $event): void
    {
        /** @var static $response */
        $response = $event->sender;

        $secure = boolval($_ENV['YII_PRODUCT_SETTINGS']['common']['useHttps']);

        /** @var Cookie $cookie */
        foreach ($response->cookies as $cookie) {
            //$cookie->sameSite = 'None';
            $cookie->secure = $secure;
        }
    }
}
