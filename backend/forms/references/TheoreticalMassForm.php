<?php

namespace backend\forms\references;

use common\models\references\OuterDiameter;
use common\models\references\TheoreticalMass;
use common\models\references\WallThickness;
use yii\base\Model;
use yii\db\ActiveQuery;

class TheoreticalMassForm extends Model
{
    public $id;
    public $outer_diameter_id;
    public $wall_thickness_id;
    public $value;

    public function rules(): array
    {
        return [
            [['outer_diameter_id', 'wall_thickness_id', 'value'], 'required'],
            [['outer_diameter_id', 'wall_thickness_id'], 'integer'],
            [['value'], 'number', 'min' => 0],
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
            [
                ['wall_thickness_id'],
                'unique',
                'targetAttribute' => ['wall_thickness_id', 'outer_diameter_id'],
                'targetClass' => TheoreticalMass::class,
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
            'outer_diameter_id' => 'Наружний диаметр',
            'wall_thickness_id' => 'Толщина стенки ГНКТ',
            'value' => 'Теоретическая масса',
        ];
    }

    /**
     * @param TheoreticalMass $model
     * @return void
     */
    public function loadData(TheoreticalMass $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
