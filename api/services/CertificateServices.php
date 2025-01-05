<?php

namespace api\services;

use api\exceptions\ValidationException;
use api\forms\certificate\CommonStepForm;
use api\forms\certificate\CylinderForm;
use api\forms\certificate\DetailTubeStepForm;
use api\forms\certificate\MeldUpdateForm;
use api\forms\certificate\NonDestructiveTestItemForm;
use api\forms\certificate\NoteForm;
use api\forms\certificate\RollUpdateForm;
use api\forms\certificate\SignatureForm;
use api\models\CertificateCommon;
use common\models\certificates\Certificate;
use common\models\certificates\Cylinder;
use common\models\certificates\Meld;
use common\models\certificates\NonDestructiveTest;
use common\models\certificates\Note;
use common\models\certificates\Roll;
use common\models\certificates\Signature;
use common\models\certificates\Status;
use common\models\references\Customer;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;

class CertificateServices
{
    private CertificateCommon $model;

    /**
     * @param CertificateCommon $model
     */
    public function __construct(CertificateCommon $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return CertificateCommon
     * @throws ValidationException
     */
    public function create(array $data): CertificateCommon
    {
        $this->model->status_id = Status::STATUS_DRAFT;
        return $this->saveCommon($data);
    }

    /**
     *  Создание/обновление общих параметров
     *
     * @param array $data
     * @return CertificateCommon
     * @throws ValidationException
     */
    public function saveCommon(array $data): CertificateCommon
    {
        $form = new CommonStepForm();
        $form->loadData($this->model);
        $form->setScenario(CommonStepForm::SCENARIO_DRAFT);
        $form->attributes = $data;

        if (!$form->validate()) {
            throw new ValidationException($form->getFirstErrors());
        }

        $this->model->attributes = $form->getAttributes();
        if (!$this->model->save()) {
            throw new ValidationException($this->model->getFirstErrors());
        }
        if ($this->model->customer) {
            $this->saveCustomer($this->model->customer);
        }
        return $this->model;
    }

    /**
     * @param string $customerName
     * @return void
     */
    private function saveCustomer(string $customerName): void
    {
        $customer = Customer::find()->where(['name' => $customerName])->one();
        if (!$customer) {
            $customer = new Customer();
            $customer->name = $customerName;
            if (!$customer->save()) {
                Yii::error($customer->getErrors(), 'customer');
            }
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Throwable
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public function saveNonDestructiveTest(array $data): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $resultErrors = [];
            $this->model->unlinkAll('nonDestructiveTests', true);

            foreach (['method_1', 'method_2', 'method_3'] as $method) {
                $controlObjectId = 'cross_seams_roll_ends';
                $itemData = $data[$controlObjectId][$method] ?? [];
                $itemErrors = $this->saveNonDestructiveTestItem($itemData, $controlObjectId);
                if ($itemErrors) {
                    $resultErrors[$controlObjectId] = $itemErrors;
                }
            }

            foreach (['longitudinal_seams', 'base_metal', 'circular_corner_seam'] as $controlObjectId) {
                $itemErrors = $this->saveNonDestructiveTestItem($data[$controlObjectId] ?? [], $controlObjectId);
                if ($itemErrors) {
                    $resultErrors[$controlObjectId] = $itemErrors;
                }
            }

            if ($resultErrors) {
                throw new ValidationException($resultErrors);
            }
            $transaction->commit();
        } catch (Exception | Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Сохранение элемента неразрушающего контроля
     *
     * @param array $data
     * @param string $controlObjectId
     * @return array
     */
    private function saveNonDestructiveTestItem(array $data, string $controlObjectId): array
    {
        $form = new NonDestructiveTestItemForm($this->model->id, $controlObjectId);
        $form->setScenario($form::SCENARIO_DRAFT);
        $form->attributes = $data;
        if (!$form->validate()) {
            return $form->getFirstErrors();
        } else {
            $model = new NonDestructiveTest();
            $model->attributes = $form->attributes;
            $model->certificate_id = $this->model->id;
            $model->control_object_id = $controlObjectId;
            if (!$model->save()) {
                return $model->getFirstErrors();
            }
        }
        return [];
    }

    /**
     * @param array $data
     * @return void
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public function saveDetailTube(array $data): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $resultErrors = [];

            $form = new DetailTubeStepForm();
            $form->loadData($this->model);
            $form->setScenario(CommonStepForm::SCENARIO_DRAFT);
            $form->attributes = $data;
            if (!$form->validate()) {
                $resultErrors = $form->getFirstErrors();
            }
            $this->model->attributes = $form->getAttributes();
            if (!$this->model->save()) {
                $resultErrors = $this->model->getFirstErrors();
            }

            foreach ($data['melds'] ?? [] as $meldId => $meldData) {
                $meldData = is_array($meldData) ? $meldData : [];
                /** @var Meld $meld */
                $meld = Meld::find()->where(['id' => intval($meldId)])->limit(1)->one();
                if ($meld) {
                    $meldService = new MeldServices($meld);
                    $errors = $meldService->update($meldData);
                    if ($errors) {
                        $resultErrors['melds'][$meldId] = $errors;
                    }
                    foreach ($meldData['rolls'] ?? [] as $rollId => $rollData) {
                        $rollData = is_array($rollData) ? $rollData : [];
                        /** @var Roll $roll */
                        $roll = Roll::find()->where(['id' => intval($rollId)])->limit(1)->one();
                        if ($roll) {
                            $rollService = new RollServices($roll);
                            $errors = $rollService->update($rollData);
                            if ($errors) {
                                $resultErrors['melds'][$meldId]['rolls'][$rollId] = $errors;
                            }
                        }
                    }
                }
            }

            if ($resultErrors) {
                throw new ValidationException($resultErrors);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Сохранение информации о барабане
     *
     * @param $data
     * @return void
     * @throws ValidationException
     */
    public function saveCylinder($data): void
    {
        $cylinder = $this->model->cylinder ?? new Cylinder();

        $form = new CylinderForm();
        $form->loadData($cylinder);
        $form->setScenario(CylinderForm::SCENARIO_DRAFT);
        $form->attributes = $data;
        $form->id = $this->model->id;

        if (!$form->validate()) {
            throw new ValidationException($form->getFirstErrors());
        }

        $cylinder->attributes = $form->getAttributes();
        if (!$cylinder->save()) {
            throw new ValidationException($cylinder->getFirstErrors());
        }
    }

    /**
     * Обновление примечаний
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public function saveNotes(array $data): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $resultErrors = [];

            foreach ($data as $noteId => $noteData) {
                $noteData = is_array($noteData) ? $noteData : [];
                /** @var Note $note */
                $note = Note::find()->where(['id' => intval($noteId)])->limit(1)->one();
                if ($note) {
                    $noteService = new NoteServices($note);
                    $errors = $noteService->update($noteData);
                    if ($errors) {
                        $resultErrors[$noteId] = $errors;
                    }
                }
            }

            if ($resultErrors) {
                throw new ValidationException($resultErrors);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Обновление подписей
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public function saveSignatures(array $data): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $resultErrors = [];

            foreach ($data as $signatureId => $signatureData) {
                $signatureData = is_array($signatureData) ? $signatureData : [];
                /** @var Signature $signature */
                $signature = Signature::find()->where(['id' => intval($signatureId)])->limit(1)->one();
                if ($signature) {
                    $signatureService = new SignatureServices($signature);
                    $errors = $signatureService->update($signatureData);
                    if ($errors) {
                        $resultErrors[$signatureId] = $errors;
                    }
                }
            }

            if ($resultErrors) {
                throw new ValidationException($resultErrors);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     *  Удаление/переаод в архив серификата
     *
     * @return void
     * @throws Throwable
     * @throws ValidationException
     * @throws \yii\db\Exception
     * @throws StaleObjectException
     */
    public function delete(): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->model->status_id == Status::STATUS_PUBLISHED) {
                $this->model->status_id = Status::STATUS_DELETED;
                if (!$this->model->save()) {
                    throw new ValidationException($this->model->getFirstErrors());
                }
            } else {
                if (!$this->model->delete()) {
                    throw new ValidationException($this->model->getFirstErrors());
                }
            }
            $transaction->commit();
        } catch (Exception | Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Восстановление удаленного сертификата в черновики
     *
     * @return void
     * @throws ValidationException
     */
    public function restore(): void
    {
        if ($this->model->status_id == Status::STATUS_DELETED) {
            $this->model->status_id = Status::STATUS_DRAFT;
            if (!$this->model->save()) {
                throw new ValidationException($this->model->getFirstErrors());
            }
        }
    }

    /**
     * Возвращение на доработку
     *
     * @return void
     * @throws ValidationException
     */
    public function refund(): void
    {
        if ($this->model->status_id == Status::STATUS_APPROVE) {
            $this->model->status_id = Status::STATUS_REFUNDED;
            if (!$this->model->save()) {
                throw new ValidationException($this->model->getFirstErrors());
            }
        }
    }

    /**
     * Копирование сертификата
     *
     * @param Certificate $oldCertificate
     * @param array $data
     * @return CertificateCommon
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public function copy(Certificate $oldCertificate, array $data): CertificateCommon
    {
        $this->model->attributes = $oldCertificate->attributes;
        $this->model->status_id = Status::STATUS_DRAFT;
        $this->model->number = null;
        $this->model->number_tube = null;
        $this->model->rfid = null;
        $this->model->created_at = null;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Общие параметры
            $this->saveCommon($data);
            $this->model->refresh();

            // Неразрушающий контроль
            foreach ($oldCertificate->nonDestructiveTests as $oldNonDestructiveTest) {
                $nonDestructiveTest = new NonDestructiveTest();
                $nonDestructiveTest->attributes = $oldNonDestructiveTest->attributes;
                $nonDestructiveTest->certificate_id = $this->model->id;
                if (!$nonDestructiveTest->save()) {
                    Yii::error($nonDestructiveTest->getFirstErrors(), __METHOD__);
                    throw new Exception('Ошибка копирования сертификата');
                }
            }

            // Плавки
            foreach ($oldCertificate->melds as $oldMeld) {
                $meld = new Meld();
                $meld->attributes = $oldMeld->attributes;
                $meld->certificate_id = $this->model->id;
                if (!$meld->save()) {
                    Yii::error($meld->getFirstErrors(), __METHOD__);
                    throw new Exception('Ошибка копирования сертификата');
                }
                // Рулоны
                foreach ($oldMeld->rolls as $oldRoll) {
                    $roll = new Roll();
                    $roll->attributes = $oldRoll->attributes;
                    $roll->meld_id = $meld->id;
                    if (!$roll->save()) {
                        Yii::error($roll->getFirstErrors(), __METHOD__);
                        throw new Exception('Ошибка копирования сертификата');
                    }
                }
            }

            // Информация о барабане
            if ($oldCertificate->cylinder) {
                $cylinder = new Cylinder();
                $cylinder->attributes = $oldCertificate->cylinder->attributes;
                $cylinder->id = $this->model->id;
                if (!$cylinder->save()) {
                    Yii::error($cylinder->getFirstErrors(), __METHOD__);
                    throw new Exception('Ошибка копирования сертификата');
                }
            }

            // Примечания
            foreach ($oldCertificate->notes as $oldNote) {
                $note = new Note();
                $note->attributes = $oldNote->attributes;
                $note->certificate_id = $this->model->id;
                if (!$note->save()) {
                    Yii::error($note->getFirstErrors(), __METHOD__);
                    throw new Exception('Ошибка копирования сертификата');
                }
            }

            // Подписи
            foreach ($oldCertificate->signatures as $oldSignature) {
                $signature = new Signature();
                $signature->attributes = $oldSignature->attributes;
                $signature->certificate_id = $this->model->id;
                if (!$signature->save()) {
                    Yii::error($signature->getFirstErrors(), __METHOD__);
                    throw new Exception('Ошибка копирования сертификата');
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $this->model;
    }


    /**
     * Ввод в эксплуатацию/отправка на согласование
     *
     * @param string $status
     * @return void
     * @throws ValidationException
     */
    public function approve(string $status): void
    {
        $errors = [];

        // Common Step
        $form = new CommonStepForm();
        $form->loadData($this->model);
        $form->setScenario(CommonStepForm::SCENARIO_PUBLISH);
        if (!$form->validate()) {
            $errors['commonStep'] = $form->getFirstErrors();
        }

        // NonDestructive Tests Step
        $tests = $this->model->getNonDestructiveTestsAsArray();
        foreach ($tests as $controlObjectId => $controlObjects)
        {
            if (is_array($controlObjects)) {
                foreach ($controlObjects as $method => $item) {
                    $form = new NonDestructiveTestItemForm($this->model->id, $controlObjectId);
                    $form->loadData($item);
                    $form->setScenario(NonDestructiveTestItemForm::SCENARIO_PUBLISH);
                    if (!$form->validate()) {
                        $errors['nonDestructiveTestsStep'][$controlObjectId][$method] = $form->getFirstErrors();
                    }
                }
            } else {
                $form = new NonDestructiveTestItemForm($this->model->id, $controlObjectId);
                $form->loadData($controlObjects);
                $form->setScenario(NonDestructiveTestItemForm::SCENARIO_PUBLISH);
                if (!$form->validate()) {
                    $errors['nonDestructiveTestsStep'][$controlObjectId] = $form->getFirstErrors();
                }
            }
        }
        foreach (['longitudinal_seams', 'base_metal',
             'circular_corner_seam', 'cross_seams_roll_ends'] as $controlObjectId) {
            if (!array_key_exists($controlObjectId, $tests)) {
                $errors['nonDestructiveTestsStep'][$controlObjectId] = 'Необходимо заполнить.';
            }
        }

        // Detail Tube Step
        $checkRollsNote = false;
        foreach ($this->model->melds as $meld) {
            $form = new MeldUpdateForm();
            $form->loadData($meld);
            $form->setScenario(MeldUpdateForm::SCENARIO_PUBLISH);
            if (!$form->validate()) {
                $errors['detailTubeStep']['melds'][$meld->id] = $form->getFirstErrors();
            }
            foreach ($meld->rolls as $roll) {
                $form = new RollUpdateForm();
                $form->loadData($roll);
                $form->setScenario(RollUpdateForm::SCENARIO_PUBLISH);
                if (!$form->validate()) {
                    $errors['detailTubeStep']['melds'][$meld->id]['rolls'][$roll->id] = $form->getFirstErrors();
                }
                if ($roll->is_use_note) {
                    $checkRollsNote = true;
                }
            }
            if (!$meld->rolls) {
                $errors['detailTubeStep']['melds'][$meld->id]['rolls'] = 'Необходимо заполнить хотя бы один рулон';
            }
        }
        if (!$this->model->melds) {
            $errors['detailTubeStep']['melds'] = 'Необходимо заполнить хотя бы одну плавку.';
        }
        if ($checkRollsNote) {
            $form = new DetailTubeStepForm();
            $form->loadData($this->model);
            $form->setScenario(DetailTubeStepForm::SCENARIO_PUBLISH);
            if (!$form->validate()) {
                $errors['detailTubeStep'] = array_merge($form->getFirstErrors(), $errors['detailTubeStep'] ?? []);
            }
        }

        // Cylinder Step
        if ($this->model->cylinder) {
            $form = new CylinderForm();
            $form->loadData($this->model->cylinder);
            $form->setScenario(CylinderForm::SCENARIO_PUBLISH);
            if (!$form->validate()) {
                $errors['cylinderStep'] = $form->getFirstErrors();
            }
        } else {
            $errors['cylinderStep'] = 'Необходимо заполнить информацию о барабане.';
        }

        // Notes Step
        foreach ($this->model->notes as $note) {
            $form = new NoteForm();
            $form->loadData($note);
            $form->setScenario(NoteForm::SCENARIO_PUBLISH);
            if (!$form->validate()) {
                $errors['notesStep'][$note->id] = $form->getFirstErrors();
            }
        }

        $checkCount = true;
        // Signatures Step
        foreach ($this->model->signatures as $signature) {
            $form = new SignatureForm();
            $form->loadData($signature);
            $form->setScenario(SignatureForm::SCENARIO_PUBLISH);
            if (!$form->validate()) {
                $checkCount = false;
                $errors['signaturesStep'][$signature->id] = $form->getFirstErrors();
            }
        }
        if ($checkCount && (count($this->model->signatures) < 2)) {
            $errors['signaturesStep'] = 'Необходимо заполнить минимум 2 подписи.';
        }

        if ($errors) {
            throw new ValidationException($errors);
        }

        $this->model->status_id = $status;
        if (!$this->model->save()) {
            throw new ValidationException($this->model->getFirstErrors());
        }
    }
}
