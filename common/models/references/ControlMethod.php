<?php

namespace common\models\references;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Метод контроля
 *
 * @property integer $id
 * @property string $name
 *
 * @property-read NdControlMethod[] $ndControlMethods
 */
class ControlMethod extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.control_method';
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

    /**
     * @return ActiveQuery
     */
    public function getNdControlMethods(): ActiveQuery
    {
        return $this->hasMany(NdControlMethod::class, ['control_method_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getAllToList(): array
    {
        return ArrayHelper::map(self::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }
}
