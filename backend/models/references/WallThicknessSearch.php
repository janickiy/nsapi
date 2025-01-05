<?php

namespace backend\models\references;

use common\models\references\WallThickness;
use yii\data\ActiveDataProvider;

/**
 *
 */
class WallThicknessSearch extends WallThickness
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'string'],
            [['name'], 'filter', 'filter' => function ($value) {
                return str_replace('.', ',', $value);
            }],
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
            'defaultOrder' => ['name' => SORT_ASC],
            'attributes' => [
                'name' => [
                    'asc' => ['value' => SORT_ASC],
                    'desc' => ['value' => SORT_DESC],
                ]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere(['ilike', 'name', $this->name]);


        return $dataProvider;
    }
}
