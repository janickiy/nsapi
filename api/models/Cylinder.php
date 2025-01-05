<?php

namespace api\models;

class Cylinder extends \common\models\certificates\Cylinder
{
    /**
     * @return string[]
     */
    public function fields(): array
    {
        return [
            'material',
            'weight',
            'diameter_core',
            'diameter_cheek',
            'width',
            'mark_nitrogen',
        ];
    }
}
