<?php

namespace api\exceptions;

/**
 * Class UnAuthorizeException
 * @package api\exceptions
 */
class UnAuthorizeException extends ApiException
{
    public function __construct()
    {
        parent::__construct(self::HTTP_UNAUTHORIZED_CODE, 'Требуется авторизация.');
    }
}
