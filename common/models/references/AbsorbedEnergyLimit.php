<?php

namespace common\models\references;

use common\models\certificates\Certificate;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Поглощенная энергия
 *
 * @property integer $id
 * @property integer $standard_id - Идентификатор стандарта
 * @property integer $wall_thickness_id - Идентификатор толщины стенки
 * @property numeric $value - Индивидуальное значение
 * @property numeric $value_average - Среднее значение
 *
 * @property-read Standard $standard
 * @property-read WallThickness $wallThickness
 */
class AbsorbedEnergyLimit extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.absorbed_energy_limit';
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            [['standard_id', 'wall_thickness_id'], 'required'],
            [['standard_id', 'wall_thickness_id'], 'integer'],
            [
                ['wall_thickness_id'],
                'unique',
                'targetAttribute' => ['standard_id','wall_thickness_id']
            ],
            [['value', 'value_average'], 'number'],
            [
                ['standard_id'],
                'exist',
                'targetClass' => Standard::class,
                'targetAttribute' => ['standard_id' => 'id']
            ],
            [
                ['wall_thickness_id'],
                'exist',
                'targetClass' => WallThickness::class,
                'targetAttribute' => ['wall_thickness_id' => 'id']
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'standard_id' => 'Стандарт',
            'wall_thickness_id' => 'Толщина стенки',
            'value' => 'Индивидуальное значение',
            'value_average' => 'Среднее значение',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStandard(): ActiveQuery
    {
        return $this->hasOne(Standard::class, ['id' => 'standard_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getWallThickness(): ActiveQuery
    {
        return $this->hasOne(WallThickness::class, ['id' => 'wall_thickness_id']);
    }

    /**
     * @param WallThickness|null $wallThickness
     * @param Certificate $certificate
     * @return AbsorbedEnergyLimit|null
     */
    public static function findByWallThickness(
        ?WallThickness $wallThickness,
        Certificate $certificate
    ): ?AbsorbedEnergyLimit
    {
        if (!$wallThickness) {
            return null;
        }
        
        /** @var AbsorbedEnergyLimit $model */
        $model = self::find()
            ->where([
                'standard_id' => $certificate->standard_id,
                'wall_thickness_id' => $wallThickness->id,
            ])
            ->limit(1)
            ->one();
        return $model;
    }
}
