<?php

namespace api\services;

use api\exceptions\ValidationException;
use api\forms\certificate\MeldCreateForm;
use api\forms\certificate\MeldUpdateForm;
use common\models\certificates\Meld;
use Throwable;
use yii\db\StaleObjectException;

class MeldServices
{
    private Meld $model;

    /**
     * @param Meld $model
     */
    public function __construct(Meld $model)
    {
        $this->model = $model;
    }

    /**
     * Создание плавки
     *
     * @param int $certificateId
     * @param array $data
     * @return Meld
     * @throws ValidationException
     */
    public function create(int $certificateId, array $data): Meld
    {
        $form = new MeldCreateForm();
        $form->setScenario(MeldCreateForm::SCENARIO_DRAFT);
        $form->attributes = $data;
        $form->certificate_id = $certificateId;

        if (!$form->validate()) {
            return throw new ValidationException($form->getFirstErrors());
        }

        $this->model->attributes = $form->getAttributes();
        if (!$this->model->save()) {
            return throw new ValidationException($this->model->getFirstErrors());
        }

        return $this->model;
    }

    /**
     *  Обновление плавки
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        $form = new MeldUpdateForm();
        $form->loadData($this->model);
        $form->setScenario(MeldUpdateForm::SCENARIO_DRAFT);
        $form->attributes = $data;

        if (!$form->validate()) {
            return $form->getFirstErrors();
        }

        $this->model->attributes = $form->getAttributes();
        if (!$this->model->save()) {
            return $this->model->getFirstErrors();
        }

        return [];
    }

    /**
     * Удаление плавки
     *
     * @return void
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete(): void
    {
        $this->model->delete();
    }
}
