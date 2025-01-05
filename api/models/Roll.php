<?php

namespace api\models;

use common\models\certificates\Certificate;
use common\models\references\HardnessLimit;

class Roll extends \common\models\certificates\Roll
{
    /**
     * @return string[]
     */
    public function fields(): array
    {
        $hardnessLimit = HardnessLimit::findByCertificate($this->meld->certificate);
        return [
            'id',
            'serial_number',
            'number',
            'wall_thickness_id',
            'wall_thickness_info' => function (self $model) {
                return new WallThicknessInfo($model->meld->certificate, $model->wall_thickness_id);
            },
            'length',
            'location_seams' => function (self $model) {
                return $model->locationSeams;
            },
            'location_seams_note',
            'grain_size',
            'grain_size_max' => function () {
                return Certificate::GRAIN_MAX;
            },
            'hardness_note',
            'is_exact_hardness_om',
            'hardness_om',
            'hardness_om_max' => function () use ($hardnessLimit) {
                return $hardnessLimit?->value;
            },
            'is_exact_hardness_ssh',
            'hardness_ssh',
            'hardness_ssh_max' => function () use ($hardnessLimit) {
                return $hardnessLimit?->value;
            },
            'is_exact_hardness_ztv',
            'hardness_ztv',
            'hardness_ztv_max' => function () use ($hardnessLimit) {
                return $hardnessLimit?->value;
            },
            'is_use_note',
            'is_fill_testing',
            'absorbed_energy_1',
            'absorbed_energy_2',
            'absorbed_energy_3',
            'fluidity',
            'fluidity_note',
            'strength',
            'relative_extension',
            'relative_extension_note',
        ];
    }
}
