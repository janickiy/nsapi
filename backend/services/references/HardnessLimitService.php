<?php

namespace backend\services\references;

use backend\forms\references\HardnessLimitForm;
use common\models\references\HardnessLimit;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class HardnessLimitService
{
    private HardnessLimit $model;

    /**
     * @param HardnessLimit $model
     */
    public function __construct(HardnessLimit $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return HardnessLimit
     * @throws ValidationException
     */
    public function save(array $attributes): HardnessLimit
    {
        $form = new HardnessLimitForm();
        $form->loadData($this->model);
        $form->attributes = $attributes;
        if (!$form->validate()) {
            throw new ValidationException(null, $form->firstErrors);
        }

        $this->model->attributes = $form->attributes;
        if (!$this->model->save()) {
            throw new ValidationException(null, $this->model->getFirstErrors());
        }

        return $this->model;
    }
}
