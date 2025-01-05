<?php

namespace common\models\references;

use common\models\certificates\Certificate;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Испытательное давление
 *
 * @property integer $id
 * @property integer $standard_id - Идентификатор стандарта
 * @property integer $hardness_id - Идентификатор группы прочности
 * @property integer $outer_diameter_id - Идентификатор наружнего диаметра
 * @property integer $wall_thickness_id - Идентификатор толщины стенки
 * @property numeric $value - Толщина
 *
 * @property-read Standard $standard
 * @property-read Hardness $hardness
 * @property-read OuterDiameter $outerDiameter
 * @property-read WallThickness $wallThickness
 */
class TestPressure extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.test_pressure';
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id'], 'required'],
            [['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id'], 'integer'],
            [
                ['wall_thickness_id'],
                'unique',
                'targetAttribute' => ['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id']
            ],
            [['value'], 'number'],
            [
                ['standard_id'],
                'exist',
                'targetClass' => Standard::class,
                'targetAttribute' => ['standard_id' => 'id']
            ],
            [
                ['hardness_id'],
                'exist',
                'targetClass' => Hardness::class,
                'targetAttribute' => ['hardness_id' => 'id']
            ],
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
            'standard_id' => 'Стандарт',
            'hardness_id' => 'Группа прочности',
            'outer_diameter_id' => 'Наружний диаметр',
            'wall_thickness_id' => 'Толщина стенки',
            'value' => 'Значение',
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
    public function getHardness(): ActiveQuery
    {
        return $this->hasOne(Hardness::class, ['id' => 'hardness_id']);
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
     * @return \api\models\references\TestPressure|null
     */
    public static function findByWallThickness(?WallThickness $wallThickness, Certificate $certificate): ?TestPressure
    {
        if (!$wallThickness) {
            return null;
        }

        /** @var TestPressure $model */
        $model = self::find()
            ->where([
                'standard_id' => $certificate->standard_id,
                'hardness_id' => $certificate->hardness_id,
                'wall_thickness_id' => $wallThickness->id,
                'outer_diameter_id' => $certificate->outer_diameter_id,
            ])
            ->limit(1)
            ->one();
        return $model;
    }
}
