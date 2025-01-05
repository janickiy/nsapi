<?php

namespace backend\services\references;

use backend\forms\references\NdControlMethodForm;
use common\models\references\NdControlMethod;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class NdControlMethodService
{
    private NdControlMethod $model;

    /**
     * @param NdControlMethod $model
     */
    public function __construct(NdControlMethod $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return NdControlMethod
     * @throws ValidationException
     */
    public function save(array $attributes): NdControlMethod
    {
        $form = new NdControlMethodForm();
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
