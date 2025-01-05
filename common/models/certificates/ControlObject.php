<?php

namespace common\models\certificates;

use yii\db\ActiveRecord;

/**
 * Объект контроля
 *
 * @property string $id
 * @property string $name
 */
class ControlObject extends ActiveRecord
{
    const
        ID_CROSS_SEAMS_ROLL_ENDS = 'cross_seams_roll_ends',
        ID_LONGITUDINAL_SEAMS = 'longitudinal_seams',
        ID_BASE_METAL = 'base_metal',
        ID_CIRCULAR_CORNER_SEAM = 'circular_corner_seam';
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.control_object';
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
