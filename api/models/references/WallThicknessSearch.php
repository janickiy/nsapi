<?php

namespace api\models\references;

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
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => ['value' => SORT_ASC],
        ]);

        $this->load($params, '');
        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere(['ilike', 'name', $this->name]);


        return $dataProvider;
    }

    /**
     * @return string[]
     */
    public function fields(): array
    {
        return [
            'id',
            'name',
            'value' => function (WallThickness $model) {
                return floatval($model->value);
            },
        ];
    }
}
