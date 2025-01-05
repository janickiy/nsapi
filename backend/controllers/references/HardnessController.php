<?php

namespace backend\controllers\references;

use backend\forms\references\HardnessForm;
use backend\helpers\ControllerHelper;
use backend\models\references\HardnessSearch;
use backend\services\references\HardnessService;
use common\models\references\Hardness;
use Exception;
use quartz\tools\modules\errorHandler\traits\BaseExceptionProcessingErrorsTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class HardnessController extends Controller
{
    use BaseExceptionProcessingErrorsTrait;

    const GRID_NAME = 'grid_hardness';

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
        $searchModel = new HardnessSearch();
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
        $model = new Hardness();
        $modelForm = new HardnessForm();
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new HardnessService($model);
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
        $modelForm = new HardnessForm();
        $modelForm->loadData($model);
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new HardnessService($model);
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
     * @return Hardness
     * @throws NotFoundHttpException
     */
    private function findModel($id): Hardness
    {
        $model = Hardness::findOne(intval($id));
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}