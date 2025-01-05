<?php

namespace backend\controllers\references;

use backend\forms\references\CustomerForm;
use backend\helpers\ControllerHelper;
use backend\models\references\CustomerSearch;
use backend\services\references\CustomerService;
use common\models\references\Customer;
use Exception;
use quartz\tools\modules\errorHandler\traits\BaseExceptionProcessingErrorsTrait;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CustomerController extends Controller
{
    use BaseExceptionProcessingErrorsTrait;

    const GRID_NAME = 'grid_customer';

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete', 'close'],
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
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ControllerHelper::setStoredUrl(self::GRID_NAME, 'index');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $modelForm = new CustomerForm();
        $modelForm->loadData($model);
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $service = new CustomerService($model);
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
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionDelete($id): Response
    {
        $model = $this->findModel($id);
        try {
            $service = new CustomerService($model);
            $service->delete();
            Yii::$app->session->setFlash('success', 'Успешно');
        } catch (Exception $e) {
            $this->processingException($e, $message);
            $this->setMessage($message);
        }
        return $this->redirect(ControllerHelper::getStoredUrl(self::GRID_NAME, 'index'));
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
     * @return Customer
     * @throws NotFoundHttpException
     */
    private function findModel($id): Customer
    {
        $model = Customer::findOne(intval($id));
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}