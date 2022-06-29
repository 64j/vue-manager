<?php

declare(strict_types=1);

namespace VueManager\Exception;

use Exception;

class NotFoundException extends Exception
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @var string
     */
    protected $message = 'Not found';
}
