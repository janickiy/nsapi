<?php

namespace backend\services\references;

use backend\forms\references\TheoreticalMassForm;
use common\models\references\TheoreticalMass;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class TheoreticalMassService
{
    private TheoreticalMass $model;

    /**
     * @param TheoreticalMass $model
     */
    public function __construct(TheoreticalMass $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return TheoreticalMass
     * @throws ValidationException
     */
    public function save(array $attributes): TheoreticalMass
    {
        $form = new TheoreticalMassForm();
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
