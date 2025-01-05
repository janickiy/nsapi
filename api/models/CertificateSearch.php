<?php

namespace api\models;

use common\models\certificates\Certificate;
use common\models\certificates\Status;
use Yii;
use yii\data\ActiveDataProvider;

/**
 *
 */
class CertificateSearch extends Certificate
{
    public $search_string;
    public $length_min;
    public $length_max;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['search_string', 'number', 'number_tube', 'rfid'], 'string'],
            [['search_string', 'number', 'number_tube', 'rfid'], 'filter', 'filter' => 'trim'],
            [['search_string', 'number', 'number_tube', 'rfid'], 'filter', 'filter' => 'strip_tags'],
            [['outer_diameter_id'], 'integer'],
            [['length_min', 'length_max'], 'number'],
            [['created_at'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    /**
     * Поиск опубликованных сертификатов
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchPublished(array $params): ActiveDataProvider
    {
        return $this->search($params, [Status::STATUS_PUBLISHED]);
    }

    /**
     * Поиск черновиков сертификатов
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchDraft(array $params): ActiveDataProvider
    {
        return $this->search($params, [Status::STATUS_DRAFT, Status::STATUS_REFUNDED]);
    }

    /**
     * Поиск удаленных сертификатов
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchDeleted(array $params): ActiveDataProvider
    {
        return $this->search($params, [Status::STATUS_DELETED]);
    }

    /**
     * Поиск сертификатов, отправленных на согласование
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchApprove(array $params): ActiveDataProvider
    {
        return $this->search($params, [Status::STATUS_APPROVE]);
    }

    /**
     * @param array $params
     * @param array $statuses
     * @return ActiveDataProvider
     */
    private function search(array $params, array $statuses = []): ActiveDataProvider
    {
        $query = self::find()->alias('c')
            ->andWhere(['c.status_id' => $statuses])
            ->joinWith(['melds m'])
            ->leftJoin(Roll::tableName() . ' r', 'r.meld_id = m.id');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_DESC],
        ]);

        $this->load($params, '');
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'and',
            ['ilike', 'c.number', $this->number],
            ['ilike', 'c.number_tube', $this->number_tube],
            ['ilike', 'c.rfid', $this->rfid],
            ['c.outer_diameter_id' => $this->outer_diameter_id],
            ['c.created_at' => $this->created_at],

        ]);

        $query->andFilterHaving(['>=', 'COALESCE(SUM(r.length), 0)', $this->length_min]);
        $query->andFilterHaving(['<=', 'COALESCE(SUM(r.length), 0)', $this->length_max]);
        $query->groupBy(['c.id']);

        $query->andFilterWhere([
            'or',
            ['ilike', 'c.number', $this->search_string],
            ['ilike', 'c.number_tube', $this->search_string],
            ['ilike', 'c.rfid', $this->search_string],
            ['ilike', 'c.customer', $this->search_string],
            ['ilike', 'c.product_type', $this->search_string],
        ]);

        return $dataProvider;
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id',
            'product_type',
            'status' => function (self $model) {
                return [
                    'id' => $model->status->id,
                    'name' => $model->status->name,
                ];
            },
            'number',
            'number_tube',
            'created_at' => function (self $model) {
                return Yii::$app->formatter->asDate($model->created_at);
            },
            'rfid'
        ];
    }
}
