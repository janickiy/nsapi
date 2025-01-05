<?php

namespace api\forms\certificate;

use common\models\certificates\Certificate;
use common\models\certificates\Cylinder;
use yii\base\Model;

class CylinderForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $id;
    public $material;
    public $weight;
    public $diameter_core;
    public $diameter_cheek;
    public $width;
    public $mark_nitrogen;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                'id', 'material', 'weight', 'diameter_core', 'diameter_cheek', 'width', 'mark_nitrogen',
            ],
            self::SCENARIO_PUBLISH => [
                'id', 'material', 'weight', 'diameter_core', 'diameter_cheek', 'width', 'mark_nitrogen',
            ]
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'required'],
            [['material', 'mark_nitrogen'], 'string', 'max' => 255],
            [['material', 'mark_nitrogen'], 'filter', 'filter' => 'trim'],
            [['material', 'mark_nitrogen'], 'filter', 'filter' => 'strip_tags'],
            [['material', 'mark_nitrogen'], 'default', 'value' => null],
            [['id'], 'integer'],
            [['weight', 'diameter_core', 'diameter_cheek', 'width'], 'number', 'min' => 0],
            [
                ['id'],
                'exist',
                'targetClass' => Certificate::class,
                'targetAttribute' => ['id' => 'id']
            ],
            [
                ['material', 'weight', 'diameter_core', 'diameter_cheek', 'width', 'mark_nitrogen'],
                'required',
                'on' => self::SCENARIO_PUBLISH
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'material' => 'Материал',
            'weight' => 'Масса',
            'diameter_core' => 'Диаметр сердечника',
            'diameter_cheek' => 'Диаметр щек',
            'width' => 'Ширина',
            'mark_nitrogen' => 'Отметка о заполнении азотом',
        ];
    }

    /**
     * @param Cylinder $cylinder
     * @return void
     */
    public function loadData(Cylinder $cylinder): void
    {
        $this->setAttributes($cylinder->attributes, false);
    }
}
