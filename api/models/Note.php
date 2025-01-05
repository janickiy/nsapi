<?php

namespace api\models;

class Note extends \common\models\certificates\Note
{
    /**
     * @return string[]
     */
    public function fields(): array
    {
        return [
            'id',
            'text',
        ];
    }
}
