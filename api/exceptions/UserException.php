<?php

namespace api\exceptions;

/**
 * Class UserException
 * @package api\exceptions
 */
class UserException extends ApiException
{
    public function __construct($message)
    {
        parent::__construct(self::HTTP_VALIDATION_CODE, $message);
    }
}
