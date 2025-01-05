<?php

namespace backend\forms\references;

use common\models\references\AbsorbedEnergyLimit;
use common\models\references\Standard;
use common\models\references\WallThickness;
use yii\base\Model;
use yii\db\ActiveQuery;

class AbsorbedEnergyLimitForm extends Model
{
    public $id;
    public $standard_id;
    public $wall_thickness_id;
    public $value;
    public $value_average;

    public function rules(): array
    {
        return [
            [['standard_id', 'wall_thickness_id', 'value', 'value_average'], 'required'],
            [['standard_id', 'wall_thickness_id'], 'integer'],
            [['value', 'value_average'], 'number', 'min' => 0],
            [
                'value_average',
                'compare',
                'compareAttribute' => 'value',
                'operator' => ">=",
                'message' => 'Среднеарифметическое значение должно быть больше или равно Индивидуальному значению'
            ],
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
            [
                ['wall_thickness_id'],
                'unique',
                'targetAttribute' => ['standard_id', 'wall_thickness_id'],
                'targetClass' => AbsorbedEnergyLimit::class,
                'filter' => function (ActiveQuery $query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                    return $query;
                },
                'message' => 'Значение уже существует',
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'standard_id' => 'Стандарт',
            'wall_thickness_id' => 'Толщина стенки ГНКТ',
            'value' => 'Индивидуальное значение, KV, Дж',
            'value_average' => 'Среднеарифметическое значение, KV, Дж',
        ];
    }

    /**
     * @param AbsorbedEnergyLimit $model
     * @return void
     */
    public function loadData(AbsorbedEnergyLimit $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
