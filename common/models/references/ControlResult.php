<?php

namespace common\models\references;

use yii\db\ActiveRecord;

/**
 * Метод контроля
 *
 * @property integer $id
 * @property string $text
 */
class ControlResult extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.control_result';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'string', 'max' => 255],
            [['text'], 'unique'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'text' => 'Результат',
        ];
    }
}
