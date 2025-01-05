<?php

namespace backend\forms\references;

use common\models\references\ControlMethod;
use common\models\references\NdControlMethod;
use yii\base\Model;
use yii\db\ActiveQuery;

class NdControlMethodForm extends Model
{
    public $id;
    public $control_method_id;
    public $name;

    public function rules(): array
    {
        return [
            [['control_method_id', 'name'], 'required'],
            [['control_method_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'filter', 'filter' => 'trim'],
            [['name'], 'filter', 'filter' => 'strip_tags'],
            [
                ['control_method_id'],
                'exist',
                'targetClass' => ControlMethod::class,
                'targetAttribute' => ['control_method_id' => 'id']
            ],
            [
                ['name'],
                'unique',
                'targetAttribute' => ['control_method_id', 'name'],
                'targetClass' => NdControlMethod::class,
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
            'control_method_id' => 'Метод контроля',
            'name' => 'Название',
        ];
    }

    /**
     * @param NdControlMethod $model
     * @return void
     */
    public function loadData(NdControlMethod $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
