<?php

namespace api\models;

class Signature extends \common\models\certificates\Signature
{
    /**
     * @return string[]
     */
    public function fields(): array
    {
        return [
            'id',
            'name',
            'position',
        ];
    }
}
