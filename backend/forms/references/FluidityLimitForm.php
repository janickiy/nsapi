<?php

namespace backend\forms\references;

use common\models\references\Hardness;
use common\models\references\Standard;
use common\models\references\FluidityLimit;
use yii\base\Model;
use yii\db\ActiveQuery;

class FluidityLimitForm extends Model
{
    public $id;
    public $standard_id;
    public $hardness_id;
    public $value_min;
    public $value_max;

    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id', 'value_min'], 'required'],
            [['standard_id', 'hardness_id'], 'integer'],
            [['value_min', 'value_max'], 'number', 'min' => 0],
            [
                'value_max',
                'compare',
                'compareAttribute' => 'value_min',
                'operator' => ">=",
                'message' => 'Значение "Не более" должно быть больше или равно значегию "Не менее"'
            ],
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
                ['hardness_id'],
                'unique',
                'targetAttribute' => ['standard_id', 'hardness_id'],
                'targetClass' => FluidityLimit::class,
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
            'hardness_id' => 'Группа прочности',
            'value_min' => 'Не менее, МПа',
            'value_max' => 'Не более, МПа',
        ];
    }

    /**
     * @param FluidityLimit $model
     * @return void
     */
    public function loadData(FluidityLimit $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
