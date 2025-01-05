<?php

namespace backend\controllers\references;

use backend\forms\references\StrengthLimitForm;
use backend\helpers\ControllerHelper;
use backend\models\references\StrengthLimitSearch;
use backend\services\references\StrengthLimitService;
use common\models\references\StrengthLimit;
use Exception;
use quartz\tools\modules\errorHandler\traits\BaseExceptionProcessingErrorsTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class StrengthLimitController extends Controller
{
    use BaseExceptionProcessingErrorsTrait;

    const GRID_NAME = 'grid_strength_limit';

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'close'],
                        'allow' => true,
                        'roles' => ['developer','admin'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new StrengthLimitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ControllerHelper::setStoredUrl(self::GRID_NAME, 'index');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $model = new StrengthLimit();
        $modelForm = new StrengthLimitForm();
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new StrengthLimitService($model);
                $service->save($modelForm->attributes);
                Yii::$app->session->setFlash('success', 'Успешно');
                return $this->redirect(ControllerHelper::getStoredUrl(self::GRID_NAME, 'index'));
            } catch (Exception $e) {
                $this->processingException($e, $message);
                $this->setMessage($message);
            }
        }
        return $this->render('edit', [
            'model' => $model,
            'modelForm' => $modelForm,
        ]);
    }

    /**
     * @param $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id): Response|string
    {
        $model = $this->findModel($id);
        $modelForm = new StrengthLimitForm();
        $modelForm->loadData($model);
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new StrengthLimitService($model);
                $service->save($modelForm->attributes);
                Yii::$app->session->setFlash('success', 'Успешно');
                return $this->redirect(ControllerHelper::getStoredUrl(self::GRID_NAME, 'index'));
            } catch (Exception $e) {
                $this->processingException($e, $message);
                $this->setMessage($message);
            }
        }
        return $this->render('edit', [
            'model' => $model,
            'modelForm' => $modelForm,
        ]);
    }

    /**
     * @return Response
     */
    public function actionClose(): Response
    {
        return $this->redirect(ControllerHelper::getStoredUrl(self::GRID_NAME, 'index'));
    }

    /**
     * @param $id
     * @return StrengthLimit
     * @throws NotFoundHttpException
     */
    private function findModel($id): StrengthLimit
    {
        $model = StrengthLimit::findOne(intval($id));
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}