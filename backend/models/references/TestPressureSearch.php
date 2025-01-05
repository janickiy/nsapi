<?php

namespace backend\models\references;

use common\models\references\TestPressure;
use yii\data\ActiveDataProvider;

/**
 *
 */
class TestPressureSearch extends TestPressure
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id'], 'integer'],
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
        $query = self::find()->alias('tp')
            ->joinWith('standard s')
            ->joinWith('hardness h')
            ->joinWith('outerDiameter od')
            ->joinWith('wallThickness th');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'standard_id' => SORT_ASC,
                'hardness_id' => SORT_ASC,
                'outer_diameter_id' => SORT_ASC,
                'wall_thickness_id' => SORT_ASC,
                'value' => SORT_ASC,
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
            'tp.standard_id' => $this->standard_id,
            'tp.hardness_id' => $this->hardness_id,
            'tp.outer_diameter_id' => $this->outer_diameter_id,
            'tp.wall_thickness_id' => $this->wall_thickness_id,
            'tp.value' => $this->value,
        ]);


        return $dataProvider;
    }
}
