<?php

namespace api\models;

class NonDestructiveTest extends \common\models\certificates\NonDestructiveTest
{
    /**
     * @return string[]
     */
    public function fields(): array
    {
        return [
            'control_method_id',
            'nd_control_method_id',
            'control_result_id',
            'note' => function () {
                return $this->note ? $this->note : null;
            },
        ];
    }
}
