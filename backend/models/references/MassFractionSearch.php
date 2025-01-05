<?php

namespace backend\models\references;

use common\models\references\MassFraction;
use yii\data\ActiveDataProvider;

/**
 *
 */
class MassFractionSearch extends MassFraction
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['hardness_id'], 'integer'],
            [['carbon', 'manganese', 'silicon', 'sulfur', 'phosphorus'], 'filter', 'filter' => function ($value) {
                return str_replace(',', '.', $value);
            }],
            [['carbon', 'manganese', 'silicon', 'sulfur', 'phosphorus'], 'number'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find()->alias('mf')
            ->joinWith('hardness h');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'hardness_id' => SORT_ASC,
            ],
            'attributes' => [
                'hardness_id' => [
                    'asc' => ['h.name' => SORT_ASC],
                    'desc' => ['h.name' => SORT_DESC],
                ],
                'carbon', 'manganese', 'silicon', 'sulfur', 'phosphorus'
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere([
            'mf.hardness_id' => $this->hardness_id,
            'mf.carbon' => $this->carbon,
            'mf.manganese' => $this->manganese,
            'mf.silicon' => $this->silicon,
            'mf.sulfur' => $this->sulfur,
            'mf.phosphorus' => $this->phosphorus,
        ]);


        return $dataProvider;
    }
}
