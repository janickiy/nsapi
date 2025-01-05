<?php

namespace backend\services\references;

use backend\forms\references\ControlResultForm;
use common\models\references\ControlResult;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class ControlResultService
{
    private ControlResult $model;

    /**
     * @param ControlResult $model
     */
    public function __construct(ControlResult $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return ControlResult
     * @throws ValidationException
     */
    public function save(array $attributes): ControlResult
    {
        $form = new ControlResultForm();
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
