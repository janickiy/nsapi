<?php

namespace backend\services\references;

use backend\forms\references\CustomerForm;
use common\models\references\Customer;
use quartz\tools\modules\errorHandler\exceptions\ValidationException;
use Throwable;
use yii\db\StaleObjectException;

class CustomerService
{
    private Customer $model;

    /**
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return Customer
     * @throws ValidationException
     */
    public function save(array $attributes): Customer
    {
        $form = new CustomerForm();
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

    /**
     * @return void
     * @throws ValidationException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete(): void
    {
        if (!$this->model->delete()) {
            throw new ValidationException(null, $this->model->getFirstErrors());
        }
    }
}
