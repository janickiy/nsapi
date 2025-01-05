<?php

namespace api\forms\certificate;

use common\models\certificates\Certificate;
use yii\base\Model;

class DetailTubeStepForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $rolls_note;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                'rolls_note'
            ],
            self::SCENARIO_PUBLISH => [
                'rolls_note'
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['rolls_note'], 'string', 'max' => 255],
            [['rolls_note'], 'filter', 'filter' => 'trim'],
            [['rolls_note'], 'filter', 'filter' => 'strip_tags'],
            [['rolls_note'], 'default', 'value' => null],
            [['rolls_note'], 'required', 'on' => self::SCENARIO_PUBLISH],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'rolls_note' => 'Сноска для рулонов',
        ];
    }

    /**
     * @param Certificate $certificate
     * @return void
     */
    public function loadData(Certificate $certificate): void
    {
        $this->setAttributes($certificate->attributes, false);
    }
}
