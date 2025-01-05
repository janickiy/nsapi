<?php

namespace common\models\references;

use common\models\certificates\Certificate;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Предел текучести
 *
 * @property integer $id
 * @property integer $standard_id - Идентификатор стандарта
 * @property integer $hardness_id - Идентификатор группы прочности
 * @property numeric $value_min - Значение не меннее
 * @property numeric $value_max - Значение не более
 *
 * @property-read Standard $standard
 * @property-read Hardness $hardness
 */
class FluidityLimit extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.fluidity_limit';
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id'], 'required'],
            [['standard_id', 'hardness_id'], 'integer'],
            [
                ['hardness_id'],
                'unique',
                'targetAttribute' => ['standard_id', 'hardness_id']
            ],
            [['value_min', 'value_max'], 'number'],
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
            'value_min' => 'Значение не меннее',
            'value_max' => 'Значение не более',
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
     * @param Certificate $certificate
     * @return FluidityLimit|null
     */
    public static function findByCertificate(Certificate $certificate): ?FluidityLimit
    {
        /** @var FluidityLimit $model */
        $model = self::find()
            ->where([
                'standard_id' => $certificate->standard_id,
                'hardness_id' => $certificate->hardness_id,
            ])
            ->limit(1)
            ->one();
        return $model;
    }
}
