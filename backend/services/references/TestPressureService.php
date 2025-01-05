<?php

namespace backend\services\references;

use backend\forms\references\TestPressureForm;
use common\models\references\TestPressure;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;

class TestPressureService
{
    private TestPressure $model;

    /**
     * @param TestPressure $model
     */
    public function __construct(TestPressure $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return TestPressure
     * @throws ValidationException
     */
    public function save(array $attributes): TestPressure
    {
        $form = new TestPressureForm();
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
