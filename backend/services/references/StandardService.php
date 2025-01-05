<?php

namespace backend\services\references;

use backend\forms\references\StandardForm;
use common\models\references\Standard;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class StandardService
{
    private Standard $model;

    /**
     * @param Standard $model
     */
    public function __construct(Standard $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return Standard
     * @throws ValidationException
     */
    public function save(array $attributes): Standard
    {
        $form = new StandardForm();
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
