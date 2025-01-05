<?php

namespace backend\models\references;

use common\models\references\HardnessLimit;
use yii\data\ActiveDataProvider;

/**
 *
 */
class HardnessLimitSearch extends HardnessLimit
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id'], 'integer'],
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
        $query = self::find()->alias('hl')
            ->joinWith('standard s')
            ->joinWith('hardness h');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'standard_id' => SORT_ASC,
                'hardness_id' => SORT_ASC,
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
                'value'
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere([
            'hl.standard_id' => $this->standard_id,
            'hl.hardness_id' => $this->hardness_id,
            'hl.value' => $this->value,
        ]);


        return $dataProvider;
    }
}
