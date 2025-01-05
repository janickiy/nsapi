<?php

namespace backend\models\references;

use common\models\references\FluidityLimit;
use yii\data\ActiveDataProvider;

/**
 *
 */
class FluidityLimitSearch extends FluidityLimit
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id'], 'integer'],
            [['value_min', 'value_max'], 'filter', 'filter' => function ($value) {
                return str_replace(',', '.', $value);
            }],
            [['value_min', 'value_max'], 'number'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find()->alias('fl')
            ->joinWith('standard s')
            ->joinWith('hardness h');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'standard_id' => SORT_ASC,
                'hardness_id' => SORT_ASC,
                'value_min' => SORT_ASC,
            ],
            'attributes' => [
                'standard_id' => [
                    'asc' => ['s.name' => SORT_ASC],
                    'desc' => ['s.name' => SORT_DESC],
                ],
                'hardness_id' => [
                    'asc' => ['h.name' => SORT_ASC],
                    'desc' => ['h.name' => SORT_DESC],
                ],
                'value_min',
                'value_max',
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere([
            'fl.standard_id' => $this->standard_id,
            'fl.hardness_id' => $this->hardness_id,
            'fl.value_min' => $this->value_min,
            'fl.value_max' => $this->value_max,
        ]);


        return $dataProvider;
    }
}
