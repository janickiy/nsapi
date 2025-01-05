<?php

namespace api\services;

use api\exceptions\ValidationException;
use api\forms\certificate\SignatureForm;
use common\models\certificates\Signature;
use Throwable;
use yii\db\StaleObjectException;

class SignatureServices
{
    private Signature $model;

    /**
     * @param Signature $model
     */
    public function __construct(Signature $model)
    {
        $this->model = $model;
    }

    /**
     * Создание подписи
     *
     * @param int $certificateId
     * @return Signature
     * @throws ValidationException
     */
    public function create(int $certificateId): Signature
    {
        $this->model->certificate_id = $certificateId;
        if (!$this->model->save()) {
            return throw new ValidationException($this->model->getFirstErrors());
        }

        return $this->model;
    }

    /**
     * Обновление подписи
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        $form = new SignatureForm();
        $form->loadData($this->model);
        $form->setScenario(SignatureForm::SCENARIO_DRAFT);
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
     * Удаление пописи
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
