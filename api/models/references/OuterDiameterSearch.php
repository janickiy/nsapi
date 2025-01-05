<?php

namespace api\models\references;

use common\models\references\OuterDiameter;
use yii\data\ActiveDataProvider;

/**
 *
 */
class OuterDiameterSearch extends OuterDiameter
{
    /**
     * @return ActiveDataProvider
     */
    public function search(): ActiveDataProvider
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => ['millimeter' => SORT_ASC],
        ]);

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
            'millimeter',
            'inch'
        ];
    }
}