<?php

namespace common\models\references;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Толщина стенки
 *
 * @property integer $id
 * @property string $name - Название
 * @property numeric $value - Толщина
 */
class WallThickness extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.wall_thickness';
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            [['name', 'value'], 'required'],
            [['name', 'value'], 'unique'],
            [['name'], 'string', 'max' => 255],
            [['value'], 'number'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'value' => 'Значение',
        ];
    }

    /**
     * @return array
     */
    public static function getAllToList(): array
    {
        return ArrayHelper::map(self::find()->orderBy(['value' => SORT_ASC])->all(), 'id', 'name');
    }
}
