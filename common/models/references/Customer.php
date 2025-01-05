<?php

namespace common\models\references;

use yii\db\ActiveRecord;

/**
 * Покупатель
 *
 * @property integer $id
 * @property string $name
 */
class Customer extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.customer';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}
