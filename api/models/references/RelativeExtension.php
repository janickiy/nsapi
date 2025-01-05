<?php

namespace api\models\references;

use common\models\certificates\Certificate;
use common\models\references\WallThickness;

/**
 *
 */
class RelativeExtension extends \common\models\references\RelativeExtension
{

    /**
     * @param WallThickness|null $wallThickness
     * @param Certificate $certificate
     * @return RelativeExtension|null
     */
    public static function findByWallThickness(?WallThickness $wallThickness, Certificate $certificate): ?RelativeExtension
    {
        if (!$wallThickness) {
            return null;
        }
        /** @var RelativeExtension $model */
        $model = self::find()
            ->where([
                'standard_id' => $certificate->standard_id,
                'hardness_id' => $certificate->hardness_id,
                'wall_thickness_id' => $wallThickness->id,
                'outer_diameter_id' => $certificate->outer_diameter_id,
            ])
            ->limit(1)
            ->one();
        return $model;
    }
}
