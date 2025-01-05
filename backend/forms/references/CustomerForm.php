<?php

namespace backend\forms\references;

use common\models\references\Customer;
use yii\base\Model;
use yii\db\ActiveQuery;

class CustomerForm extends Model
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
                'targetClass' => Customer::class,
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
     * @param Customer $model
     * @return void
     */
    public function loadData(Customer $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
