<?php

namespace api\exceptions;

/**
 * Class UserException
 * @package api\exceptions
 */
class NotFoundException extends ApiException
{
    public function __construct($message)
    {
        parent::__construct(self::HTTP_NOT_FOUND_CODE, $message);
    }
}
