<?php

namespace api\models;

use api\models\references\RelativeExtension;
use api\models\references\TestPressure;
use api\models\references\TheoreticalMass;
use common\models\certificates\Certificate;
use common\models\references\AbsorbedEnergyLimit;
use common\models\references\FluidityLimit;
use common\models\references\StrengthLimit;
use common\models\references\WallThickness;
use yii\base\Model;

class WallThicknessInfo extends Model
{
    private Certificate $certificate;
    private ?WallThickness $wallThickness;

    public function __construct(Certificate $certificate, ?int $wallThicknessId, array $config = [])
    {
        $this->certificate = $certificate;

        $this->wallThickness = WallThickness::find()->where(['id' => intval($wallThicknessId)])->one();
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        $strengthLimit = StrengthLimit::findByCertificate($this->certificate);
        $fluidityLimit = FluidityLimit::findByCertificate($this->certificate);
        $absorbedEnergyLimit = AbsorbedEnergyLimit::findByWallThickness($this->wallThickness, $this->certificate);

        return $this->wallThickness
            ? [
                'theoretical_mass' => function (self $model) {
                    $theoreticalMass = TheoreticalMass::findByWallThickness($model->wallThickness, $model->certificate);
                    return $theoreticalMass?->value;
                },
                'test_pressure' => function (self $model) {
                    $testPressure = TestPressure::findByWallThickness($model->wallThickness, $model->certificate);
                    return $testPressure?->value;
                },
                'strength_min' => function () use ($strengthLimit) {
                    return $strengthLimit?->value;
                },
                'fluidity_min' => function () use ($fluidityLimit) {
                    return $fluidityLimit?->value_min;
                },
                'fluidity_max' => function () use ($fluidityLimit) {
                    return $fluidityLimit?->value_max;
                },
                'relative_extension_min' => function (self $model) {
                    $relativeExtension = RelativeExtension::findByWallThickness(
                        $model->wallThickness,
                        $model->certificate
                    );
                    return $relativeExtension?->value;
                },
                'absorbed_energy_1_min' => function () use ($absorbedEnergyLimit) {
                    return $absorbedEnergyLimit?->value;
                },
                'absorbed_energy_2_min' => function () use ($absorbedEnergyLimit) {
                    return $absorbedEnergyLimit?->value;
                },
                'absorbed_energy_3_min' => function () use ($absorbedEnergyLimit) {
                    return $absorbedEnergyLimit?->value;
                },
            ]
            : [];
    }
}
