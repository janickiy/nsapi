<?php

namespace backend\models\references;

use common\models\references\TheoreticalMass;
use yii\data\ActiveDataProvider;

/**
 *
 */
class TheoreticalMassSearch extends TheoreticalMass
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['outer_diameter_id', 'wall_thickness_id'], 'integer'],
            [['value'], 'filter', 'filter' => function ($value) {
                return str_replace(',', '.', $value);
            }],
            [['value'], 'number'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find()->alias('tm')
            ->joinWith('outerDiameter od')
            ->joinWith('wallThickness th');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'outer_diameter_id' => SORT_ASC,
                'wall_thickness_id' => SORT_ASC,
                'value' => SORT_ASC,
            ],
            'attributes' => [
                'outer_diameter_id' => [
                    'asc' => ['od.millimeter' => SORT_ASC],
                    'desc' => ['od.millimeter' => SORT_DESC],
                ],
                'wall_thickness_id' => [
                    'asc' => ['th.value' => SORT_ASC],
                    'desc' => ['th.value' => SORT_DESC],
                ],
                'value'
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere([
            'tm.outer_diameter_id' => $this->outer_diameter_id,
            'tm.wall_thickness_id' => $this->wall_thickness_id,
            'tm.value' => $this->value,
        ]);


        return $dataProvider;
    }
}
