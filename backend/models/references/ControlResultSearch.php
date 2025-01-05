<?php

namespace backend\models\references;

use common\models\references\ControlResult;
use yii\data\ActiveDataProvider;

/**
 *
 */
class ControlResultSearch extends ControlResult
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['text'], 'string'],
            [['text'], 'filter', 'filter' => 'trim'],
            [['text'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'text' => SORT_ASC,
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere(['ilike', 'text', $this->text]);

        return $dataProvider;
    }
}
