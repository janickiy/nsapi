<?php

namespace backend\services\references;

use backend\forms\references\StrengthLimitForm;
use common\models\references\StrengthLimit;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class StrengthLimitService
{
    private StrengthLimit $model;

    /**
     * @param StrengthLimit $model
     */
    public function __construct(StrengthLimit $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return StrengthLimit
     * @throws ValidationException
     */
    public function save(array $attributes): StrengthLimit
    {
        $form = new StrengthLimitForm();
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
