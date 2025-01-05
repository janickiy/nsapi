<?php

namespace backend\controllers\references;

use backend\forms\references\OuterDiameterForm;
use backend\helpers\ControllerHelper;
use backend\models\references\OuterDiameterSearch;
use backend\services\references\OuterDiameterService;
use common\models\references\OuterDiameter;
use Exception;
use quartz\tools\modules\errorHandler\traits\BaseExceptionProcessingErrorsTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class OuterDiameterController extends Controller
{
    use BaseExceptionProcessingErrorsTrait;

    const GRID_NAME = 'grid_outer_diameter';

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
        $searchModel = new OuterDiameterSearch();
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
        $model = new OuterDiameter();
        $modelForm = new OuterDiameterForm();
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new OuterDiameterService($model);
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
        $modelForm = new OuterDiameterForm();
        $modelForm->loadData($model);
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new OuterDiameterService($model);
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
     * @return OuterDiameter
     * @throws NotFoundHttpException
     */
    private function findModel($id): OuterDiameter
    {
        $model = OuterDiameter::findOne(intval($id));
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}