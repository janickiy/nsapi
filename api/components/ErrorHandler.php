<?php

namespace api\components;

use api\exceptions\ApiException;
use Exception;
use quartz\tools\modules\errorHandler\exceptions\base\BaseValidationException;
use Yii;
use yii\web\ErrorHandler as BaseErrorHandler;

/**
 * Class ErrorHandler
 * @package api\components
 */
class ErrorHandler extends BaseErrorHandler
{
    /**
     * @return void
     * @param Exception $exception
     */
    public function handleException($exception): void
    {
        $this->exception = $exception;

        $this->unregister();

        /** Логирование ошибки */
        $this->logException($exception);
        if ($this->discardExistingOutput) {
            $this->clearOutput();
        }

        switch (true) {
            case $exception instanceof ApiException:
                // Наши ошибки
                $responseData = [
                    'message' => $exception->getMessage(),
                ];
                if ($errorData = $exception->getDataError()) {
                    $responseData['errors'] = $errorData;
                }
                break;
            case $exception instanceof BaseValidationException:
                $responseData = [
                    'message' => null,
                ];
                if ($errorData = $exception->getErrorData()) {
                    $responseData['errors'] = $errorData;
                }
                break;
            default:
                $responseData = [
                    'message' => YII_DEBUG ? $exception->getMessage() : 'Внутренняя ошибка сервера',
                ];
        }

        /** В дебаг режиме расширяем ошибку за счет Trace */
        if (YII_DEBUG) {
            $responseData['trace'] = $exception->getTraceAsString();
        }

        Yii::$app->response->data = $responseData;
        Yii::$app->response->setStatusCode($exception->statusCode ?? 500);
        Yii::$app->response->send();

        Yii::getLogger()->flush(true);

        $this->exception = null;
    }
}
