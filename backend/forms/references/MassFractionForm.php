<?php

namespace backend\forms\references;

use common\models\references\Hardness;
use common\models\references\MassFraction;
use yii\base\Model;
use yii\db\ActiveQuery;

class MassFractionForm extends Model
{
    public $id;
    public $hardness_id;
    public $carbon;
    public $manganese;
    public $silicon;
    public $sulfur;
    public $phosphorus;

    public function rules(): array
    {
        return [
            [['hardness_id', 'carbon', 'manganese', 'silicon', 'sulfur', 'phosphorus'], 'required'],
            [['hardness_id'], 'integer'],
            [['carbon', 'manganese', 'silicon', 'sulfur', 'phosphorus'], 'number', 'min' => 0],
            [
                ['hardness_id'],
                'exist',
                'targetClass' => Hardness::class,
                'targetAttribute' => ['hardness_id' => 'id']
            ],
            [
                ['hardness_id'],
                'unique',
                'targetClass' => MassFraction::class,
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
            'hardness_id' => 'Группа прочности',
            'carbon' => 'Углерод C',
            'manganese' => 'Марганец Mn',
            'silicon' => 'Кремний Si',
            'sulfur' => 'Сера S',
            'phosphorus' => 'Фосфор P',
        ];
    }

    /**
     * @param MassFraction $model
     * @return void
     */
    public function loadData(MassFraction $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
