<?php

namespace backend\models\references;

use common\models\references\Standard;
use yii\data\ActiveDataProvider;

/**
 *
 */
class StandardSearch extends Standard
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'prefix'], 'string'],
            [['name', 'prefix'], 'filter', 'filter' => 'trim'],
            [['name', 'prefix'], 'filter', 'filter' => 'strip_tags'],
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
                'name' => SORT_ASC,
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere(['ilike', 'name', $this->name]);
        $query->andFilterWhere(['ilike', 'prefix', $this->prefix]);

        return $dataProvider;
    }
}
