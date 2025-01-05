<?php

namespace api\models;

use api\models\references\MassFraction;
use common\models\certificates\Certificate;
use yii\db\ActiveQuery;

class Meld extends \common\models\certificates\Meld
{
    /**
     * @return ActiveQuery
     */
    public function getRolls(): ActiveQuery
    {
        return $this->hasMany(Roll::class, ['meld_id' => 'id'])->orderBy(['serial_number' => SORT_ASC]);
    }

    /**
     * @return string[]
     */
    public function fields(): array
    {
        $massFraction = MassFraction::findByCertificate($this->certificate);
        return [
            'id',
            'number',
            'sekv',
            'sekv_note',
            'chemical_c',
            'chemical_c_max' => function () use ($massFraction) {
                return $massFraction ?->carbon;
            },
            'chemical_mn',
            'chemical_mn_max' => function () use ($massFraction) {
                return $massFraction ?->manganese;
            },
            'chemical_si',
            'chemical_si_max' => function () use ($massFraction) {
                return $massFraction ?->silicon;
            },
            'chemical_s',
            'chemical_s_max' => function () use ($massFraction) {
                return $massFraction ?->sulfur;
            },
            'chemical_p',
            'chemical_p_max' => function () use ($massFraction) {
                return $massFraction ?->phosphorus;
            },
            'dirty_type_a',
            'dirty_type_a_max' => function () {
                return Certificate::DIRTY_MAX;
            },
            'dirty_type_b',
            'dirty_type_b_max' => function () {
                return Certificate::DIRTY_MAX;
            },
            'dirty_type_c',
            'dirty_type_c_max' => function () {
                return Certificate::DIRTY_MAX;
            },
            'dirty_type_d',
            'dirty_type_d_max' => function () {
                return Certificate::DIRTY_MAX;
            },
            'dirty_type_ds',
            'dirty_type_ds_max' => function () {
                return Certificate::DIRTY_MAX;
            },
            'rolls' => function () {
                $result = [];
                foreach ($this->rolls as $roll) {
                    $result[$roll->id] = $roll;
                }
                return $result;
            }
        ];
    }
}
