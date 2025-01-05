<?php

namespace backend\forms\references;

use common\models\references\WallThickness;
use yii\base\Model;
use yii\db\ActiveQuery;

class WallThicknessForm extends Model
{
    public $id;
    public $value;

    public function rules(): array
    {
        return [
            [['value'], 'required'],
            [['value'], 'number', 'min' => 0],
            [
                ['value'],
                'unique',
                'targetClass' => WallThickness::class,
                'filter' => function (ActiveQuery $query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                    return $query;
                },
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'value' => 'Значение'
        ];
    }

    /**
     * @param WallThickness $model
     * @return void
     */
    public function loadData(WallThickness $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
