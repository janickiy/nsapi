<?php

namespace api\exceptions;

/**
 * Class UnAuthorizeException
 * @package api\exceptions
 */
class ForbiddenException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            self::HTTP_FORBIDDEN_CODE,
            'Вам не разрешено производить данное действие.'
        );
    }
}
