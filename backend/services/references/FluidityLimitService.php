<?php

namespace backend\services\references;

use backend\forms\references\FluidityLimitForm;
use common\models\references\FluidityLimit;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class FluidityLimitService
{
    private FluidityLimit $model;

    /**
     * @param FluidityLimit $model
     */
    public function __construct(FluidityLimit $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return FluidityLimit
     * @throws ValidationException
     */
    public function save(array $attributes): FluidityLimit
    {
        $form = new FluidityLimitForm();
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
