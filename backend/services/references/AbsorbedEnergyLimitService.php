<?php

namespace backend\services\references;

use backend\forms\references\AbsorbedEnergyLimitForm;
use common\models\references\AbsorbedEnergyLimit;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class AbsorbedEnergyLimitService
{
    private AbsorbedEnergyLimit $model;

    /**
     * @param AbsorbedEnergyLimit $model
     */
    public function __construct(AbsorbedEnergyLimit $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return AbsorbedEnergyLimit
     * @throws ValidationException
     */
    public function save(array $attributes): AbsorbedEnergyLimit
    {
        $form = new AbsorbedEnergyLimitForm();
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
