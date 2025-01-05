<?php

namespace backend\forms\references;

use common\models\references\OuterDiameter;
use yii\base\Model;
use yii\db\ActiveQuery;

class OuterDiameterForm extends Model
{
    public $id;
    public $millimeter;
    public $inch;

    public function rules(): array
    {
        return [
            [['millimeter', 'inch'], 'required'],
            [['millimeter', 'inch'], 'number', 'min' => 0],
            [
                ['millimeter', 'inch'],
                'unique',
                'targetClass' => OuterDiameter::class,
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
            'millimeter' => 'Диаметр в миллиметрах',
            'inch' => 'Диаметр в дюймах'
        ];
    }

    /**
     * @param OuterDiameter $model
     * @return void
     */
    public function loadData(OuterDiameter $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
