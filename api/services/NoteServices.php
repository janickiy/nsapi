<?php

namespace api\services;

use api\exceptions\ValidationException;
use api\forms\certificate\NoteForm;
use common\models\certificates\Note;
use Throwable;
use yii\db\StaleObjectException;

class NoteServices
{
    private Note $model;

    /**
     * @param Note $model
     */
    public function __construct(Note $model)
    {
        $this->model = $model;
    }

    /**
     * Создание примечания
     *
     * @param int $certificateId
     * @return Note
     * @throws ValidationException
     */
    public function create(int $certificateId): Note
    {
        $this->model->certificate_id = $certificateId;
        if (!$this->model->save()) {
            return throw new ValidationException($this->model->getFirstErrors());
        }

        return $this->model;
    }

    /**
     * Обновление примечания
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        $form = new NoteForm();
        $form->loadData($this->model);
        $form->setScenario(NoteForm::SCENARIO_DRAFT);
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
     * Удаление примечания
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
