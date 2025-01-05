<?php

namespace backend\forms\references;

use common\models\references\ControlResult;
use yii\base\Model;
use yii\db\ActiveQuery;

class ControlResultForm extends Model
{
    public $id;
    public $text;

    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'string', 'max' => 255],
            [['text'], 'filter', 'filter' => 'trim'],
            [['text'], 'filter', 'filter' => 'strip_tags'],
            [
                ['text'],
                'unique',
                'targetClass' => ControlResult::class,
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
            'text' => 'Текст',
        ];
    }

    /**
     * @param ControlResult $model
     * @return void
     */
    public function loadData(ControlResult $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
