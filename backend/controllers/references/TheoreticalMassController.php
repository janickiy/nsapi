<?php

namespace backend\controllers\references;

use backend\forms\references\TheoreticalMassForm;
use backend\helpers\ControllerHelper;
use backend\models\references\TheoreticalMassSearch;
use backend\services\references\TheoreticalMassService;
use common\models\references\TheoreticalMass;
use Exception;
use quartz\tools\modules\errorHandler\traits\BaseExceptionProcessingErrorsTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TheoreticalMassController extends Controller
{
    use BaseExceptionProcessingErrorsTrait;

    const GRID_NAME = 'grid_theoretical_mass';

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
        $searchModel = new TheoreticalMassSearch();
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
        $model = new TheoreticalMass();
        $modelForm = new TheoreticalMassForm();
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new TheoreticalMassService($model);
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
        $modelForm = new TheoreticalMassForm();
        $modelForm->loadData($model);
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new TheoreticalMassService($model);
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
     * @return TheoreticalMass
     * @throws NotFoundHttpException
     */
    private function findModel($id): TheoreticalMass
    {
        $model = TheoreticalMass::findOne(intval($id));
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}