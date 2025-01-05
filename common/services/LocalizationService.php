<?php

namespace common\services;

use quartz\localization\models\Language;
use yii\base\Exception;
use yii\db\Query;

/**
 * Class LocalizationService
 * @package common\services
 */
class LocalizationService
{
    /**
     * основная директория, где будут храниться локализации для фронтов
     * @var string
     */
    protected $dir;

    /**
     * текущий язык
     * @var Language
     */
    protected $language;

    /**
     * LocalizationService constructor.
     * @param Language $language
     */
    public function __construct(Language $language)
    {
        $this->language = $language;
        $this->dir = \Yii::getAlias('@root') . \Yii::$app->params['localization_dir'] . '/';
    }

    /**
     * Добавлен новый язык - создает новый json файл с переводом
     * @return bool
     */
    public function create()
    {
        $file = $this->dir . $this->language->id . '.json';
        return $this->generateFile($file);
    }

    /**
     * Обновляет название файла, если изменили название языка
     * @param Language $newLanguage
     * @return bool
     */
    public function update(Language $newLanguage)
    {
        $file = $this->dir . $this->language->id . '.json';
        if ($this->language->id !== $newLanguage->id && file_exists($file)) {
            return rename($file, $this->dir . $newLanguage->id . '.json');
        }

        return true;
    }

    /**
     * Обновляет файл, если в переводах языка что-то изменилось
     * @return bool
     */
    public function updateMessage()
    {
        $file = $this->dir . $this->language->id . '.json';
        return $this->generateFile($file);
    }

    /**
     * Создание и запись в файл
     * @param string $file
     * @return bool
     */
    protected function generateFile($file)
    {
        $arrWord = [];

        try {
            $messages = (new Query())
                ->select('source_message.id, category, message, translation')
                ->from('source_message')
                ->leftJoin('message', 'message.id=source_message.id')
                ->where(['message.language' => $this->language->id]);

            foreach ($messages->each() as $key => $message) {
                $arrWord[$message['category'] . '_' . $message['message']] = $message['translation'];
            }

            file_put_contents($file, json_encode($arrWord, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            chmod($file, 0755);
        } catch (Exception $e) {
            \Yii::error(__CLASS__ . ' Error writing in file');

            return false;
        }

        return true;
    }
}
