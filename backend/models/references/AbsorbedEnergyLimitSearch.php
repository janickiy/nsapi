<?php

namespace backend\models\references;

use common\models\references\AbsorbedEnergyLimit;
use yii\data\ActiveDataProvider;

/**
 *
 */
class AbsorbedEnergyLimitSearch extends AbsorbedEnergyLimit
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['standard_id', 'wall_thickness_id'], 'integer'],
            [['value', 'value_average'], 'filter', 'filter' => function ($value) {
                return str_replace(',', '.', $value);
            }],
            [['value', 'value_average'], 'number'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find()->alias('ael')
            ->joinWith('standard s')
            ->joinWith('wallThickness th');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'standard_id' => SORT_ASC,
                'wall_thickness_id' => SORT_ASC,
                'value' => SORT_ASC,
            ],
            'attributes' => [
                'standard_id' => [
                    'asc' => ['s.name' => SORT_ASC],
                    'desc' => ['s.name' => SORT_DESC],
                ],
                'wall_thickness_id' => [
                    'asc' => ['th.value' => SORT_ASC],
                    'desc' => ['th.value' => SORT_DESC],
                ],
                'value',
                'value_average'
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere([
            'ael.standard_id' => $this->standard_id,
            'ael.wall_thickness_id' => $this->wall_thickness_id,
            'ael.value' => $this->value,
            'ael.value_average' => $this->value_average,
        ]);


        return $dataProvider;
    }
}
