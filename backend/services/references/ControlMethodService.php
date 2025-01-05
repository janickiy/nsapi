<?php

namespace backend\services\references;

use backend\forms\references\ControlMethodForm;
use common\models\references\ControlMethod;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class ControlMethodService
{
    private ControlMethod $model;

    /**
     * @param ControlMethod $model
     */
    public function __construct(ControlMethod $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return ControlMethod
     * @throws ValidationException
     */
    public function save(array $attributes): ControlMethod
    {
        $form = new ControlMethodForm();
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
