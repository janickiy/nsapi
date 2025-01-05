<?php

namespace api\models\references;

use common\models\certificates\Certificate;

/**
 *
 */
class MassFraction extends \common\models\references\MassFraction
{

    /**
     * @param Certificate $certificate
     * @return MassFraction|null
     */
    public static function findByCertificate(Certificate $certificate): ?MassFraction
    {
        /** @var MassFraction $model */
        $model = self::find()
            ->where([
                'hardness_id' => $certificate->hardness_id,
            ])
            ->limit(1)
            ->one();
        return $model;
    }
}
