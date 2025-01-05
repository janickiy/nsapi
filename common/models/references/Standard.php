<?php

namespace common\models\references;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Стандарт
 *
 * @property integer $id
 * @property string $name
 * @property string $prefix
 * @property boolean $is_show_absorbed_energy
 */
class Standard extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.standard';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'prefix'], 'required'],
            [['name', 'prefix'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return string[]
     */
     function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'prefix' => 'Префикс',
        ];
    }

    /**
     * @return array
     */
    public static function getAllToList(): array
    {
        return ArrayHelper::map(self::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }
}
