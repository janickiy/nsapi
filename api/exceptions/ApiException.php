<?php

namespace api\exceptions;

use yii\web\HttpException;

/**
 * @property-read array dataError
 */
class ApiException extends HttpException
{
    const
        HTTP_OK_CODE = 200,
        HTTP_VALIDATION_CODE = 400,
        HTTP_UNAUTHORIZED_CODE = 401,
        HTTP_FORBIDDEN_CODE = 403,
        HTTP_NOT_FOUND_CODE = 404,
        HTTP_INTERNAL_ERROR_CODE = 500;

    protected array $_dataError;

    /**
     * ApiException constructor.
     *
     * @param int $httpCode
     * @param string|null $message
     * @param array $dataError
     */
    public function __construct(int $httpCode, ?string $message = null, array $dataError = []) {
        $this->message = $message;
        $this->_dataError = $dataError;
        parent::__construct($httpCode, $message);
    }

    /**
     * Вернуть данные ошибки
     *
     * @return array
     */
    public function getDataError(): array
    {
        return $this->_dataError;
    }
}
