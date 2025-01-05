<?php

namespace api\forms\certificate;

use common\models\certificates\Note;
use yii\base\Model;

class NoteForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $text;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => ['text'],
            self::SCENARIO_PUBLISH => ['text'],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['text'], 'string', 'max' => 255],
            [['text'], 'filter', 'filter' => 'trim'],
            [['text'], 'filter', 'filter' => 'strip_tags'],
            [['text'], 'default', 'value' => null],
            [['text'], 'required', 'on' => self::SCENARIO_PUBLISH],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'text' => 'Текст примечания',
        ];
    }

    /**
     * @param Note $note
     * @return void
     */
    public function loadData(Note $note): void
    {
        $this->setAttributes($note->attributes, false);
    }
}
