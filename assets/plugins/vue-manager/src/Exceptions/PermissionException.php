<?php

declare(strict_types=1);

namespace VueManager\Exceptions;

use Exception;

class PermissionException extends Exception
{
    /**
     * @var int
     */
    protected $code = 403;

    /**
     * @var string
     */
    protected $message = 'No Permissions';
}
