<?php

namespace api\services;

use api\exceptions\UserException;
use api\exceptions\ValidationException;
use api\forms\certificate\RollCreateForm;
use api\forms\certificate\RollUpdateForm;
use common\models\certificates\Certificate;
use common\models\certificates\Meld;
use common\models\certificates\Roll;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;

class RollServices
{
    private Roll $model;

    /**
     * @param Roll $model
     */
    public function __construct(Roll $model)
    {
        $this->model = $model;
    }

    /**
     * Создание рулона
     *
     * @param Meld $meld
     * @param array $data
     * @return Roll
     * @throws ValidationException
     */
    public function create(Meld $meld, array $data): Roll
    {
        $form = new RollCreateForm();
        $form->setScenario(RollCreateForm::SCENARIO_DRAFT);
        $form->attributes = $data;
        $form->meld_id = $meld->id;

        if (!$form->validate()) {
            return throw new ValidationException($form->getFirstErrors());
        }

        $lastSerialNumber = $meld->certificate->getRolls()->max('serial_number');

        $this->model->attributes = $form->getAttributes();
        $this->model->serial_number = $lastSerialNumber ? $lastSerialNumber + 1 : 1;
        if (!$this->model->save()) {
            return throw new ValidationException($this->model->getFirstErrors());
        }

        return $this->model;
    }

    /**
     *  Обновление рулона
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        $form = new RollUpdateForm();
        $form->loadData($this->model);
        $form->setScenario(RollUpdateForm::SCENARIO_DRAFT);
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
     * Удаление рулона
     *
     * @return void
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete(): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Roll::updateAllCounters(
                ['serial_number' => -1],
                [
                    'AND',
                    ['meld_id' => $this->model->meld->certificate->getMelds()->select('id')->column()],
                    ['>', 'serial_number', $this->model->serial_number],
                ]
            );
            $this->model->delete();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Обновление сортировки рулонов
     *
     * @param Certificate $certificate
     * @param array $data
     * @return void
     * @throws UserException
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public static function updateSort(Certificate $certificate, array $data): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($certificate->rolls as $roll) {
                $sort = array_search($roll->id, $data);
                if ($sort !== false) {
                    $roll->serial_number = $sort + 1;
                    if (!$roll->save()) {
                        throw new ValidationException($roll->getFirstErrors());
                    }
                } else {
                    throw new UserException('Не все рулоны указаны');
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
