<?php

namespace backend\models\references;

use common\models\references\NdControlMethod;
use yii\data\ActiveDataProvider;

/**
 *
 */
class NdControlMethodSearch extends NdControlMethod
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['control_method_id'], 'integer'],
            [['name'], 'string'],
            [['name'], 'filter', 'filter' => 'trim'],
            [['name'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find()->alias('n')
            ->joinWith('controlMethod m');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'control_method_id' => SORT_ASC,
                'name' => SORT_ASC,
            ],
            'attributes' => [
                'control_method_id' => [
                    'asc' => ['m.name' => SORT_ASC],
                    'desc' => ['m.name' => SORT_DESC],
                ],
                'name'
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere([
            'n.control_method_id' => $this->control_method_id,
        ]);
        $query->andFilterWhere(['ilike', 'n.name', $this->name]);


        return $dataProvider;
    }
}
