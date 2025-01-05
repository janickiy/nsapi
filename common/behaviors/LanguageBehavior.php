<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

/**
 * Class LanguageBehavior
 * @package common\behaviors
 */
class LanguageBehavior extends Behavior
{
    /**
     * @return string[]
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'setLangByUrl',
        ];
    }

    /**
     * Устанавливает локаль по url
     * @return void
     */
    public function setLangByUrl()
    {
        preg_match('/^\/(.*)\//iU', Yii::$app->request->url, $matches);

        if (array_key_exists(isset($matches[1]) ? $matches[1] : '', Yii::$app->params['languages'])) {
            Yii::$app->language = $matches[1];
        } else {
            Yii::$app->language = Yii::$app->params['defaultLanguage'];
        }
    }

    /**
     * Возвращает языковой код для i18n по локали
     * @param string $lang
     * @return string
     */
    public static function getNameMessagesByLang($lang)
    {
        return $lang . '-' . mb_convert_case($lang, MB_CASE_UPPER, "UTF-8");
    }
}
