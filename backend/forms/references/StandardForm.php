<?php

namespace backend\forms\references;

use common\models\references\Standard;
use yii\base\Model;
use yii\db\ActiveQuery;

class StandardForm extends Model
{
    public $id;
    public $name;
    public $prefix;

    public function rules(): array
    {
        return [
            [['name', 'prefix'], 'required'],
            [['name', 'prefix'], 'string', 'max' => 255],
            [['name', 'prefix'], 'filter', 'filter' => 'trim'],
            [['name', 'prefix'], 'filter', 'filter' => 'strip_tags'],
            [
                ['name'],
                'unique',
                'targetClass' => Standard::class,
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
            'prefix' => 'Префикс',
        ];
    }

    /**
     * @param Standard $model
     * @return void
     */
    public function loadData(Standard $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
