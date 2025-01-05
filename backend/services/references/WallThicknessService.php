<?php

namespace backend\services\references;

use backend\forms\references\WallThicknessForm;
use common\models\references\WallThickness;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class WallThicknessService
{
    private WallThickness $model;

    /**
     * @param WallThickness $model
     */
    public function __construct(WallThickness $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return WallThickness
     * @throws ValidationException
     */
    public function save(array $attributes): WallThickness
    {
        $form = new WallThicknessForm();
        $form->loadData($this->model);
        $form->attributes = $attributes;
        if (!$form->validate()) {
            throw new ValidationException(null, $form->firstErrors);
        }

        $this->model->attributes = $form->attributes;
        $this->model->name = number_format($form->value, 2, ',');
        if (!$this->model->save()) {
            throw new ValidationException(null, $this->model->getFirstErrors());
        }

        return $this->model;
    }
}