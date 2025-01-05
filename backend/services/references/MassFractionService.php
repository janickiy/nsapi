<?php

namespace backend\services\references;

use backend\forms\references\MassFractionForm;
use common\models\references\MassFraction;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class MassFractionService
{
    private MassFraction $model;

    /**
     * @param MassFraction $model
     */
    public function __construct(MassFraction $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return MassFraction
     * @throws ValidationException
     */
    public function save(array $attributes): MassFraction
    {
        $form = new MassFractionForm();
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
