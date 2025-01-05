<?php

namespace api\forms\certificate;

use common\models\certificates\Meld;
use common\models\certificates\Roll;
use yii\base\Model;

class RollCreateForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public int $meld_id;
    public $number;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                '!meld_id', 'number'
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['number'], 'string'],
            [['number'], 'filter', 'filter' => 'trim'],
            [['number'], 'filter', 'filter' => 'strip_tags'],
            [['number'], 'default', 'value' => null],
            [['meld_id', 'number'], 'required'],
            [['meld_id'], 'integer'],
            [
                ['meld_id'],
                'exist',
                'targetClass' => Meld::class,
                'targetAttribute' => ['meld_id' => 'id']
            ],
            [
                ['number'],
                'unique',
                'targetClass' => Roll::class,
                'targetAttribute' => ['meld_id', 'number'],
                'message' => "Номер рулона уже используется"
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'meld_id' => 'Плавка',
            'number' => 'Номер рулона',
        ];
    }
}
