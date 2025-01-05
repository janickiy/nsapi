<?php

namespace common\models\certificates;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Плавка
 *
 * @property integer $id
 * @property string $material Материал изготовления
 * @property numeric $weight Масса барабана
 * @property numeric $diameter_core Диаметр сердечника
 * @property numeric $diameter_cheek Диаметр щек
 * @property numeric $width Ширина
 * @property string $mark_nitrogen Отметка о заполнении азотом
 *
 * @property-read Certificate $certificate
 */
class Cylinder extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.cylinder';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'required'],
            [['material', 'mark_nitrogen'], 'string'],
            [['id'], 'integer'],
            [['weight', 'diameter_core', 'diameter_cheek', 'width'], 'number'],
            [
                ['id'],
                'exist',
                'targetClass' => Certificate::class,
                'targetAttribute' => ['id' => 'id']
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
     * @return ActiveQuery
     */
    public function getCertificate(): ActiveQuery
    {
        return $this->hasOne(Certificate::class, ['id' => 'id']);
    }
}
