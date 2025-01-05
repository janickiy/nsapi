<?php

namespace api\forms\certificate;

use common\models\certificates\Signature;
use yii\base\Model;

class SignatureForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $name;
    public $position;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => ['name', 'position'],
            self::SCENARIO_PUBLISH => ['name', 'position'],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'position'], 'string', 'max' => 255],
            [['name', 'position'], 'filter', 'filter' => 'trim'],
            [['name', 'position'], 'filter', 'filter' => 'strip_tags'],
            [['name', 'position'], 'default', 'value' => null],
            [['name', 'position'], 'required', 'on' => self::SCENARIO_PUBLISH],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'ФИО',
            'position' => 'Должность',
        ];
    }

    /**
     * @param Signature $signature
     * @return void
     */
    public function loadData(Signature $signature): void
    {
        $this->setAttributes($signature->attributes, false);
    }
}
