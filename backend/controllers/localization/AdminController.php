<?php

namespace backend\controllers\localization;

use common\services\LocalizationService;
use quartz\localization\models\Language;

/**
 * Class AdminController
 * @package backend\controllers\localization
 */
class AdminController extends \quartz\localization\controllers\AdminController
{
    /** @var Language|null  */
    protected $oldLanguage = null;

    public function beforeAction($action)
    {
        if ($id = \Yii::$app->request->get('id') ?? false) {
            $this->oldLanguage = Language::findOne($id);
        }
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $data = \Yii::$app->request->post();

        if ($data) {
            switch ($action->id) {
                case 'create':
                    $res = $this->afterCreate($data);
                    break;
                case 'update':
                    $res = $this->afterUpdate($data);
                    break;
                case 'update-messages':
                    $res = $this->afterUpdateMessages($data);
                    break;
                default:
                    $res = true;
            }

            if (!$res) {
                \Yii::error(__CLASS__ . 'Localization error');
            }
        }

        return parent::afterAction($action, $result);
    }

    /**
     * Обновление файлов локализаций после
     * создания новых меток
     * @param array $data
     * @return bool
     */
    public function afterCreate(array $data)
    {
        $language = Language::findOne($data['Language']['id']);
        if (empty($language)) {
            return false;
        }
        return (new LocalizationService($language))->create();
    }

    /**
     * Обновление файлов локализаций после
     * изменения названия языка
     * @param array $data
     * @return bool
     */
    public function afterUpdate(array $data)
    {
        $newLanguage = Language::findOne(Language::findOne($data['Language']['id']));
        if (empty($newLanguage) || empty($this->oldLanguage)) {
            return false;
        }
        return (new LocalizationService($this->oldLanguage))->update($newLanguage);
    }

    /**
     * Обновление файлов локализаций после
     * изменения переводов в метках
     * @param array $data
     * @return bool
     */
    public function afterUpdateMessages($data)
    {
        if (empty($this->oldLanguage)) {
            return false;
        }
        return (new LocalizationService($this->oldLanguage))->updateMessage();
    }
}
