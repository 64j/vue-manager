<?php

declare(strict_types=1);

namespace VueManager\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    /**
     * @var int
     */
    protected $code = 401;

    /**
     * @var string
     */
    protected $message = 'Unauthorized';
}
