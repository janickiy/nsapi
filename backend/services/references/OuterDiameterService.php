<?php

namespace backend\services\references;

use backend\forms\references\OuterDiameterForm;
use common\models\references\OuterDiameter;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class OuterDiameterService
{
    private OuterDiameter $model;

    /**
     * @param OuterDiameter $model
     */
    public function __construct(OuterDiameter $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return OuterDiameter
     * @throws ValidationException
     */
    public function save(array $attributes): OuterDiameter
    {
        $form = new OuterDiameterForm();
        $form->loadData($this->model);
        $form->attributes = $attributes;
        if (!$form->validate()) {
            throw new ValidationException(null, $form->firstErrors);
        }

        $this->model->attributes = $form->attributes;
        $this->model->name = number_format($form->millimeter, 2, ',');
        if (!$this->model->save()) {
            throw new ValidationException(null, $this->model->getFirstErrors());
        }

        return $this->model;
    }
}
