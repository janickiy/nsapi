<?php

namespace common\models\references;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Внешний диаметр
 *
 * @property integer $id
 * @property string $name - Название
 * @property numeric $millimeter - Диаметр в миллиметрах
 * @property numeric $inch - Диаметр в дюмах
 */
class OuterDiameter extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.outer_diameter';
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            [['name', 'millimeter', 'inch'], 'required'],
            [['name', 'millimeter', 'inch'], 'unique'],
            [['name'], 'string', 'max' => 255],
            [['millimeter', 'inch'], 'number'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'millimeter' => 'Диаметр в миллиметрах',
            'inch' => 'Диаметр в дюймах',
        ];
    }

    /**
     * @return array
     */
    public static function getAllToList(): array
    {
        return ArrayHelper::map(self::find()->orderBy(['millimeter' => SORT_ASC])->all(), 'id', 'name');
    }
}
