<?php

namespace backend\forms\references;

use common\models\references\Hardness;
use common\models\references\Standard;
use common\models\references\StrengthLimit;
use yii\base\Model;
use yii\db\ActiveQuery;

class StrengthLimitForm extends Model
{
    public $id;
    public $standard_id;
    public $hardness_id;
    public $value;

    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id', 'value'], 'required'],
            [['standard_id', 'hardness_id'], 'integer'],
            [['value'], 'number', 'min' => 0],
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
                'targetClass' => StrengthLimit::class,
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
            'value' => 'Предел прочности',
        ];
    }

    /**
     * @param StrengthLimit $model
     * @return void
     */
    public function loadData(StrengthLimit $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
