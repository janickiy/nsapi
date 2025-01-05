<?php

namespace api\forms\certificate;

use common\models\certificates\Certificate;
use common\models\certificates\Meld;
use yii\base\Model;

class MeldCreateForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public int $certificate_id;
    public $number;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                '!certificate_id', 'number'
            ]
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
            [['certificate_id'], 'integer'],
            [['certificate_id', 'number'], 'required'],
            [
                ['certificate_id'],
                'exist',
                'targetClass' => Certificate::class,
                'targetAttribute' => ['certificate_id' => 'id']
            ],
            [
                ['number'],
                'unique',
                'targetClass' => Meld::class,
                'targetAttribute' => ['certificate_id', 'number'],
                'message' => "Номер плавки уже используется"
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'certificate_id' => 'Сертификат',
            'number' => 'Номер плавки',
        ];
    }
}
