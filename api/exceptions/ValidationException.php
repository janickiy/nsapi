<?php

namespace api\exceptions;

/**
 * Class ValidationException
 * @package api\exceptions
 */
class ValidationException extends ApiException
{
    public function __construct($dataError)
    {
        parent::__construct(self::HTTP_VALIDATION_CODE, null, $dataError);
    }
}
