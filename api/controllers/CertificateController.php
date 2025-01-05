<?php

namespace api\controllers;

use api\exceptions\ForbiddenException;
use api\exceptions\NotFoundException;
use api\exceptions\UserException;
use api\exceptions\ValidationException;
use api\models\CertificateCommon;
use api\models\CertificateSearch;
use api\models\Cylinder;
use api\models\references\MassFraction;
use api\models\WallThicknessInfo;
use api\services\CertificateServices;
use api\services\generate\CertificateGenerateService;
use api\services\MeldServices;
use api\services\NoteServices;
use api\services\RollServices;
use api\services\SignatureServices;
use common\models\certificates\Certificate;
use common\models\certificates\Meld;
use common\models\certificates\Note;
use common\models\certificates\Roll;
use common\models\certificates\Signature;
use common\models\certificates\Status;
use common\models\references\HardnessLimit;
use common\models\User;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;

/**
 *
 */
class CertificateController extends ApiController
{
    /**
     * @return array
     */
    function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'list-published', 'list-draft', 'list-deleted', 'list-approve',
                            'create', 'common-step', 'update-common-step',
                            'update-non-destructive-test-step', 'non-destructive-test-step',
                            'create-meld', 'delete-meld', 'create-roll', 'delete-roll',
                            'detail-tube-step', 'update-detail-tube-step',
                            'cylinder-step', 'cylinder-update-step',
                            'create-note', 'delete-note', 'note-step', 'update-note-step',
                            'create-signature', 'delete-signature', 'signature-step', 'update-signature-step',
                            'wall-thickness-info', 'copy', 'download', 'all-fields', 'approve',
                            'rolls-sort-step', 'update-rolls-sort-step',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'delete', 'restore', 'refund',
                        ],
                        'allow' => true,
                        'roles' => ['moderator', 'developer', 'admin'],
                    ],
                ],
            ]
        ]);
    }

    /**
     * Список черновиков
     *
     * @return ActiveDataProvider
     */
    public function actionListDraft(): ActiveDataProvider
    {
        $searchModel = new CertificateSearch();
        return $searchModel->searchDraft(Yii::$app->request->queryParams);
    }

    /**
     * Список опубликованных
     *
     * @return ActiveDataProvider
     */
    public function actionListPublished(): ActiveDataProvider
    {
        $searchModel = new CertificateSearch();
        return $searchModel->searchPublished(Yii::$app->request->queryParams);
    }

    /**
     * Список удаленных
     *
     * @return ActiveDataProvider
     */
    public function actionListDeleted(): ActiveDataProvider
    {
        $searchModel = new CertificateSearch();
        return $searchModel->searchDeleted(Yii::$app->request->queryParams);
    }

    /**
     * Список отправленных на согласование
     *
     * @return ActiveDataProvider
     */
    public function actionListApprove(): ActiveDataProvider
    {
        $searchModel = new CertificateSearch();
        return $searchModel->searchApprove(Yii::$app->request->queryParams);
    }

    /**
     * Сохраненные поля первого шага
     *
     * @param $id
     * @return CertificateCommon
     * @throws NotFoundException
     */
    public function actionCommonStep($id): CertificateCommon
    {
        return $this->findCertificate(intval($id));
    }

    /**
     *  Создание сертификата
     *
     * @return array
     * @throws ValidationException
     */
    public function actionCreate(): array
    {
        $service = new CertificateServices(new CertificateCommon());
        $certificate = $service->create(Yii::$app->request->post() ?? []);
        return [
            'id' => $certificate->id,
        ];
    }

    /**
     * Редактирование общих параметров
     *
     * @param $id
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionUpdateCommonStep($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        $this->checkEditRights($certificate);

        $service = new CertificateServices($certificate);
        $service->saveCommon(Yii::$app->request->post() ?? []);
    }

    /**
     *  Удаление/архивирование
     *
     * @param $id
     * @return void
     * @throws Exception
     * @throws NotFoundException
     * @throws StaleObjectException
     * @throws Throwable
     * @throws ValidationException
     */
    public function actionDelete($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        //TODO добавить проверки на удаление

        $service = new CertificateServices($certificate);
        $service->delete();
    }

    /**
     * Восстановление в черновики
     *
     * @param $id
     * @return void
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionRestore($id): void
    {
        $certificate = $this->findCertificate(intval($id));

        $service = new CertificateServices($certificate);
        $service->restore();
    }

    /**
     * Возврат на доработку
     *
     * @param $id
     * @return void
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionRefund($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        if ($certificate->status_id != Status::STATUS_APPROVE) {
            throw new NotFoundException("Сертификат не найден.");
        }

        $service = new CertificateServices($certificate);
        $service->refund();
    }

    /**
     * Скачивания файла сертификата
     *
     * @param $id
     * @return void
     * @throws NotFoundException
     * @throws InvalidConfigException
     */
    public function actionDownload($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        $fileName = sprintf(
            "certificate_%s_gnkt_%s.xls",
            $certificate->number,
            $certificate->number_tube
        );
        $service = new CertificateGenerateService($certificate);
        $xls = $service->generateCertificate();
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        $xls->save("php://output");
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundException
     */
    public function actionAllFields($id): array
    {
        $certificate = $this->findCertificate(intval($id));

        return [
            'commonStep' => $certificate->toArray(),
            'nonDestructiveTestsStep' => $certificate->getNonDestructiveTestsAsArray(),
            'detailTubeStep' => $certificate->getDetailTube(),
            'cylinderStep' => $certificate->cylinder,
            'notesStep' => $certificate->getNotesAsArray(),
            'signaturesStep' => $certificate->getSignaturesAsArray(),
        ];
    }

    /**
     * Сохраненные поля первого шага
     *
     * @param $id
     * @return array
     * @throws NotFoundException
     */
    public function actionNonDestructiveTestStep($id): array
    {
        $certificate = $this->findCertificate(intval($id));
        return $certificate->getNonDestructiveTestsAsArray();
    }

    /**
     * Сохранение неразрушающего контроля
     *
     * @param $id
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws Throwable
     * @throws Exception
     */
    public function actionUpdateNonDestructiveTestStep($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        $this->checkEditRights($certificate);

        $service = new CertificateServices($certificate);
        $service->saveNonDestructiveTest(Yii::$app->request->post() ?? []);
    }

    /**
     * Добавление плавки
     *
     * @param $idCertificate
     * @return array
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionCreateMeld($idCertificate): array
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $service = new MeldServices(new Meld());
        $meld = $service->create($certificate->id, Yii::$app->request->post() ?? []);

        $massFraction = MassFraction::findByCertificate($certificate);
        return [
            'id' => $meld->id,
            'chemical_c_max' => $massFraction ?->carbon,
            'chemical_mn_max' => $massFraction ?->manganese,
            'chemical_si_max' => $massFraction ?->silicon,
            'chemical_s_max' => $massFraction ?->sulfur,
            'chemical_p_max' => $massFraction ?->phosphorus,
            'dirty_type_a_max' => Certificate::DIRTY_MAX,
            'dirty_type_b_max' => Certificate::DIRTY_MAX,
            'dirty_type_c_max' => Certificate::DIRTY_MAX,
            'dirty_type_d_max' => Certificate::DIRTY_MAX,
            'dirty_type_ds_max' => Certificate::DIRTY_MAX,
        ];
    }

    /**
     * Удаление плавки
     *
     * @param $idCertificate
     * @param $idMeld
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteMeld($idCertificate, $idMeld): void
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $meld = $this->findMeld($certificate, intval($idMeld));
        $service = new MeldServices($meld);
        $service->delete();
    }

    /**
     * Добавление рулона
     *
     * @param $idCertificate
     * @param $idMeld
     * @return array
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionCreateRoll($idCertificate, $idMeld): array
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $meld = $this->findMeld($certificate, intval($idMeld));

        $service = new RollServices(new Roll());
        $roll = $service->create($meld, Yii::$app->request->post() ?? []);

        $hardnessLimit = HardnessLimit::findByCertificate($certificate);
        return [
            'id' => $roll->id,
            'serial_number' => $roll->serial_number,
            'grain_size_max' => Certificate::GRAIN_MAX,
            'hardness_om_max' => $hardnessLimit ?->value,
            'hardness_ssh_max' => $hardnessLimit ?->value,
            'hardness_ztv_max' => $hardnessLimit ?->value,
        ];
    }

    /**
     * Удаление рулона
     *
     * @param $idCertificate
     * @param $idMeld
     * @param $idRoll
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteRoll($idCertificate, $idMeld, $idRoll): void
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $meld = $this->findMeld($certificate, intval($idMeld));
        $roll = $this->findRoll($meld, intval($idRoll));

        $service = new RollServices($roll);
        $service->delete();
    }

    /**
     * Сохранение детальной информации о трубе
     *
     * @param $id
     * @return void
     * @throws Exception
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionUpdateDetailTubeStep($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        $this->checkEditRights($certificate);

        $service = new CertificateServices($certificate);
        $service->saveDetailTube(Yii::$app->request->post() ?? []);
    }

    /**
     * Детальная информация о трубе
     *
     * @param $id
     * @return array
     * @throws NotFoundException
     */
    public function actionDetailTubeStep($id): array
    {
        $certificate = $this->findCertificate(intval($id));

        return $certificate->getDetailTube();
    }

    /**
     * @param $id
     * @return Roll[]
     * @throws NotFoundException
     */
    public function actionRollsSortStep($id): array
    {
        $result = [];

        $certificate = $this->findCertificate(intval($id));
        foreach ($certificate->rolls as $roll) {
            $result[] = ['id' => $roll->id, 'serial_number' => $roll->serial_number, 'number' => $roll->number];
        }
        return $result;
    }

    /**
     * Обновление сортировки рулонов
     *
     * @param $id
     * @return void
     * @throws NotFoundException
     * @throws ValidationException
     * @throws UserException
     */
    public function actionUpdateRollsSortStep($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        RollServices::updateSort($certificate, Yii::$app->request->post() ?? []);
    }

    /**
     * Информация о барабане
     *
     * @param $id
     * @return Cylinder
     * @throws NotFoundException
     */
    public function actionCylinderStep($id): Cylinder
    {
        $certificate = $this->findCertificate(intval($id));
        return $certificate->cylinder ?? new Cylinder();
    }

    /**
     * Сохранение информации о барабане
     *
     * @param $id
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionCylinderUpdateStep($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        $this->checkEditRights($certificate);

        $service = new CertificateServices($certificate);
        $service->saveCylinder(Yii::$app->request->post() ?? []);
    }

    /**
     * Добавление примечания
     *
     * @param $idCertificate
     * @return array
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionCreateNote($idCertificate): array
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $service = new NoteServices(new Note());
        $note = $service->create($certificate->id);

        return [
            'id' => $note->id,
        ];
    }

    /**
     * Удаление примечания
     *
     * @param $idCertificate
     * @param $idNote
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteNote($idCertificate, $idNote): void
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $note = $this->findNote($certificate, intval($idNote));

        $service = new NoteServices($note);
        $service->delete();
    }

    /**
     * Примечания
     *
     * @param $id
     * @return array
     * @throws NotFoundException
     */
    public function actionNoteStep($id): array
    {
        $certificate = $this->findCertificate(intval($id));

        return $certificate->getNotesAsArray();
    }

    /**
     * Обновление примечаний
     *
     * @param $id
     * @return void
     * @throws Exception
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionUpdateNoteStep($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        $this->checkEditRights($certificate);

        $service = new CertificateServices($certificate);
        $service->saveNotes(Yii::$app->request->post() ?? []);
    }

    /**
     * Добавление подписи
     *
     * @param $idCertificate
     * @return array
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionCreateSignature($idCertificate): array
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $service = new SignatureServices(new Signature());
        $signature = $service->create($certificate->id);

        return [
            'id' => $signature->id,
        ];
    }

    /**
     * Удаление подписи
     *
     * @param $idCertificate
     * @param $idSignature
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteSignature($idCertificate, $idSignature): void
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        $this->checkEditRights($certificate);

        $signature = $this->findSignature($certificate, intval($idSignature));

        $service = new SignatureServices($signature);
        $service->delete();
    }

    /**
     * Подписи
     *
     * @param $id
     * @return array
     * @throws NotFoundException
     */
    public function actionSignatureStep($id): array
    {
        $certificate = $this->findCertificate(intval($id));

        return $certificate->getSignaturesAsArray();
    }

    /**
     * Обновление подписей
     *
     * @param $id
     * @return void
     * @throws Exception
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionUpdateSignatureStep($id): void
    {
        $certificate = $this->findCertificate(intval($id));
        $this->checkEditRights($certificate);

        $service = new CertificateServices($certificate);
        $service->saveSignatures(Yii::$app->request->post() ?? []);
    }

    /**
     * Параметры толщины стенки
     *
     * @param $idCertificate
     * @param $idWallThickness
     * @return WallThicknessInfo
     * @throws NotFoundException
     */
    public function actionWallThicknessInfo($idCertificate, $idWallThickness): WallThicknessInfo
    {
        $certificate = $this->findCertificate(intval($idCertificate));
        return new WallThicknessInfo($certificate, $idWallThickness);
    }

    /**
     * Копирование сертификата
     *
     * @param $id
     * @return array
     * @throws Exception
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionCopy($id): array
    {
        $oldCertificate = $this->findCertificate(intval($id));

        $service = new CertificateServices(new CertificateCommon());
        $certificate = $service->copy($oldCertificate, Yii::$app->request->post() ?? []);
        return [
            'id' => $certificate->id,
        ];
    }

    /**
     * Отправка на согласование/Ввод в эксплуатацию
     *
     * @param $id
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function actionApprove($id): void
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $certificate = $this->findCertificate(intval($id));
        $this->checkEditRights($certificate);

        $service = new CertificateServices($certificate);
        $service->approve($user->isUser() ? Status::STATUS_APPROVE : Status::STATUS_PUBLISHED);
    }

    /**
     * @param int $id
     * @return CertificateCommon
     * @throws NotFoundException
     */
    private function findCertificate(int $id): CertificateCommon
    {
        /** @var CertificateCommon $model */
        $model = CertificateCommon::find()->where(['id' => $id])->limit(1)->one();
        if (!$model) {
            throw new NotFoundException('Сертификат не найден.');
        }
        return $model;
    }

    /**
     * @param CertificateCommon $certificate
     * @param int $id
     * @return Meld
     * @throws NotFoundException
     */
    private function findMeld(CertificateCommon $certificate,  int $id): Meld
    {
        /** @var Meld $model */
        $model = Meld::find()->where(['id' => $id, 'certificate_id' => $certificate->id])->limit(1)->one();
        if (!$model) {
            throw new NotFoundException('Плавка не нейдена.');
        }
        return $model;
    }

    /**
     * @param Meld $meld
     * @param int $id
     * @return Roll
     * @throws NotFoundException
     */
    private function findRoll(Meld $meld,  int $id): Roll
    {
        /** @var Roll $model */
        $model = Roll::find()->where(['id' => $id, 'meld_id' => $meld->id])->limit(1)->one();
        if (!$model) {
            throw new NotFoundException('Рулон не нейден.');
        }
        return $model;
    }

    /**
     * @param CertificateCommon $certificate
     * @param int $id
     * @return Note
     * @throws NotFoundException
     */
    private function findNote(CertificateCommon $certificate,  int $id): Note
    {
        /** @var Note $model */
        $model = Note::find()->where(['id' => $id, 'certificate_id' => $certificate->id])->limit(1)->one();
        if (!$model) {
            throw new NotFoundException('Примечание не нейдено.');
        }
        return $model;
    }

    /**
     * @param CertificateCommon $certificate
     * @param int $id
     * @return Signature
     * @throws NotFoundException
     */
    private function findSignature(CertificateCommon $certificate,  int $id): Signature
    {
        /** @var Signature $model */
        $model = Signature::find()->where(['id' => $id, 'certificate_id' => $certificate->id])->limit(1)->one();
        if (!$model) {
            throw new NotFoundException('Подпись не нейдена.');
        }
        return $model;
    }

    /**
     * @param CertificateCommon $certificate
     * @return void
     * @throws ForbiddenException
     */
    private function checkEditRights(CertificateCommon $certificate): void
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        switch ($certificate->status_id) {
            case Status::STATUS_PUBLISHED:
            case Status::STATUS_DELETED:
                throw new ForbiddenException();
            case Status::STATUS_APPROVE:
                if ($user->isUser()) {
                    throw new ForbiddenException();
                }
        }
    }
}
