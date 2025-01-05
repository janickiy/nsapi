<?php

namespace common\models\references;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Толщина стенки
 *
 * @property integer $id
 * @property integer $hardness_id - Идентификатор группы прочности
 * @property numeric $carbon - Углерод C
 * @property numeric $manganese - Марганец Mn
 * @property numeric $silicon - Кремний Si
 * @property numeric $sulfur - Сера S
 * @property numeric $phosphorus - Фосфор P
 *
 * @property-read Hardness $hardness
 */
class MassFraction extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'references.mass_fraction';
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            [['hardness_id'], 'required'],
            [['hardness_id'], 'integer'],
            [['hardness_id'], 'unique'],
            [['carbon', 'manganese', 'silicon', 'sulfur', 'phosphorus'], 'number'],
            [
                ['hardness_id'],
                'exist',
                'targetClass' => Hardness::class,
                'targetAttribute' => ['hardness_id' => 'id']
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'hardness_id' => 'Группа прочности',
            'carbon' => 'Углерод C',
            'manganese' => 'Марганец Mn',
            'silicon' => 'Кремний Si',
            'sulfur' => 'Сера S',
            'phosphorus' => 'Фосфор P',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getHardness(): ActiveQuery
    {
        return $this->hasOne(Hardness::class, ['id' => 'hardness_id']);
    }
}
