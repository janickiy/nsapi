<?php

namespace common\models\references;

use common\models\certificates\Certificate;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Толщина стенки
 *
 * @property integer $id
 * @property integer $outer_diameter_id - Идентификатор наружнего диаметра
 * @property integer $wall_thickness_id - Идентификатор толщины стенки
 * @property numeric $value - Масса
 *
 * @property-read OuterDiameter $outerDiameter
 * @property-read WallThickness $wallThickness
 */
class TheoreticalMass extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.theoretical_mass';
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            [['outer_diameter_id', 'wall_thickness_id'], 'required'],
            [['outer_diameter_id', 'wall_thickness_id'], 'integer'],
            [['wall_thickness_id'], 'unique', 'targetAttribute' => ['wall_thickness_id', 'outer_diameter_id']],
            [['value'], 'number'],
            [
                ['outer_diameter_id'],
                'exist',
                'targetClass' => OuterDiameter::class,
                'targetAttribute' => ['outer_diameter_id' => 'id']
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
            'outer_diameter_id' => 'Наружний диаметр',
            'wall_thickness_id' => 'Толщина стенки',
            'value' => 'Значение',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOuterDiameter(): ActiveQuery
    {
        return $this->hasOne(OuterDiameter::class, ['id' => 'outer_diameter_id']);
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
     * @return \api\models\references\TheoreticalMass|null
     */
    public static function findByWallThickness(?WallThickness $wallThickness, Certificate $certificate): ?TheoreticalMass
    {
        if (!$wallThickness) {
            return null;
        }
        /** @var TheoreticalMass $model */
        $model = self::find()
            ->where([
                'wall_thickness_id' => $wallThickness->id,
                'outer_diameter_id' => $certificate->outer_diameter_id,
            ])
            ->limit(1)
            ->one();
        return $model;
    }
}
