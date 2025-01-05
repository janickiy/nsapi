<?php

namespace common\models\references;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * НД на метод контроля
 *
 * @property integer $id
 * @property integer $control_method_id
 * @property string $name
 *
 * @property-read ControlMethod $controlMethod
 */
class NdControlMethod extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.nd_control_method';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'control_method_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [
                ['control_method_id'],
                'exist',
                'targetClass' => ControlMethod::class,
                'targetAttribute' => ['control_method_id' => 'id']
            ],
            [['name'], 'unique', 'targetAttribute' => ['control_method_id', 'name']],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'control_method_id' => 'Метод контроля'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getControlMethod(): ActiveQuery
    {
        return $this->hasOne(ControlMethod::class, ['id' => 'control_method_id']);
    }
}
