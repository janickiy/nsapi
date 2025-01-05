<?php

namespace backend\services\references;

use backend\forms\references\RelativeExtensionForm;
use common\models\references\RelativeExtension;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class RelativeExtensionService
{
    private RelativeExtension $model;

    /**
     * @param RelativeExtension $model
     */
    public function __construct(RelativeExtension $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return RelativeExtension
     * @throws ValidationException
     */
    public function save(array $attributes): RelativeExtension
    {
        $form = new RelativeExtensionForm();
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
