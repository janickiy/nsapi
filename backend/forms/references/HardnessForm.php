<?php

namespace backend\forms\references;

use common\models\references\Hardness;
use yii\base\Model;
use yii\db\ActiveQuery;

class HardnessForm extends Model
{
    public $id;
    public $name;

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'filter', 'filter' => 'trim'],
            [['name'], 'filter', 'filter' => 'strip_tags'],
            [
                ['name'],
                'unique',
                'targetClass' => Hardness::class,
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
            'name' => 'Название',
        ];
    }

    /**
     * @param Hardness $model
     * @return void
     */
    public function loadData(Hardness $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
