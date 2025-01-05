<?php

namespace backend\services\references;

use backend\forms\references\HardnessForm;
use common\models\references\Hardness;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class HardnessService
{
    private Hardness $model;

    /**
     * @param Hardness $model
     */
    public function __construct(Hardness $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return Hardness
     * @throws ValidationException
     */
    public function save(array $attributes): Hardness
    {
        $form = new HardnessForm();
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
