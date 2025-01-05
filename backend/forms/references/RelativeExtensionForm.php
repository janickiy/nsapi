<?php

namespace backend\forms\references;

use common\models\references\Hardness;
use common\models\references\OuterDiameter;
use common\models\references\Standard;
use common\models\references\RelativeExtension;
use common\models\references\WallThickness;
use yii\base\Model;
use yii\db\ActiveQuery;

class RelativeExtensionForm extends Model
{
    public $id;
    public $standard_id;
    public $hardness_id;
    public $outer_diameter_id;
    public $wall_thickness_id;
    public $value;

    public function rules(): array
    {
        return [
            [['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id', 'value'], 'required'],
            [['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id'], 'integer'],
            [['value'], 'number', 'min' => 0],
            [
                ['standard_id'],
                'exist',
                'targetClass' => Standard::class,
                'targetAttribute' => ['standard_id' => 'id']
            ],
            [
                ['hardness_id'],
                'exist',
                'targetClass' => Hardness::class,
                'targetAttribute' => ['hardness_id' => 'id']
            ],
            [
                ['outer_diameter_id'],
                'exist',
                'targetClass' => OuterDiameter::class,
                'targetAttribute' => ['outer_diameter_id' => 'id']
            ],
            [
                ['wall_thickness_id'],
                'exist',
                'targetClass' => WallThickness::class,
                'targetAttribute' => ['wall_thickness_id' => 'id']
            ],
            [
                ['wall_thickness_id'],
                'unique',
                'targetAttribute' => ['standard_id', 'hardness_id', 'wall_thickness_id', 'outer_diameter_id'],
                'targetClass' => RelativeExtension::class,
                'filter' => function (ActiveQuery $query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                    return $query;
                },
                'message' => 'Значение уже существует',
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'standard_id' => 'Стандарт',
            'hardness_id' => 'Группа прочности',
            'outer_diameter_id' => 'Наружний диаметр',
            'wall_thickness_id' => 'Толщина стенки ГНКТ',
            'value' => 'Относительное удлинение',
        ];
    }

    /**
     * @param RelativeExtension $model
     * @return void
     */
    public function loadData(RelativeExtension $model): void
    {
        $this->attributes = $model->attributes;
        $this->id = $model->id;
    }
}
