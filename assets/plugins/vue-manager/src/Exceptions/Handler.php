<?php

declare(strict_types=1);

namespace VueManager\Exceptions;

use Error;
use Throwable;

class Handler
{
    /**
     * @var int
     */
    protected int $defaultCode = 500;

    /**
     * @var string
     */
    protected string $defaultMessage = 'Internal Server Error';

    /**
     * @var bool
     */
    protected bool $debug = true;

    /**
     * @return void
     */
    public function registerErrorHandling(): void
    {
        error_reporting(-1);

        set_error_handler(function ($level, $message, $file = '', $line = 0) {
            $this->handleError($level, $message, $file, $line);
        });

        set_exception_handler(function ($e) {
            $this->handleException($e);
        });

        register_shutdown_function(function () {
            $this->handleShutdown();
        });
    }

    /**
     * @param $level
     * @param $message
     * @param string $file
     * @param int $line
     * @param array $context
     * @return void
     */
    protected function handleError($level, $message, string $file = '', int $line = 0, array $context = []): void
    {
        if (error_reporting() & $level) {
            $code = $this->defaultCode;
            $message = $message ?: $this->defaultMessage;
            $add = $this->debug ? [
                'line' => $line,
                'file' => str_replace(dirname(__DIR__, 3), '', $file),
            ] : [];

            $this->report([
                    'code' => $code,
                    'message' => $message,
                ] + $add, $code);
        }
    }

    /**
     * @param \Throwable $e
     * @return void
     */
    protected function handleException(Throwable $e): void
    {
        $code = $e->getCode() ?: $this->defaultCode;
        $message = $e->getMessage() ?: $this->defaultMessage;
        $add = $this->debug ? [
            'line' => $e->getLine(),
            'file' => str_replace(dirname(__DIR__, 3), '', $e->getFile()),
        ] : [];

        $this->report([
                'code' => $code,
                'message' => $message,
            ] + $add, $code);
    }

    /**
     * @return void
     */
    protected function handleShutdown(): void
    {
        if (!is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->handleException($this->fatalErrorFromPhpError($error, 0));
        }
    }

    /**
     * @param array $error
     * @param int|null $traceOffset
     * @return \Error
     */
    protected function fatalErrorFromPhpError(array $error, int $traceOffset = null): Error
    {
        return new Error($error['message'], 0);
    }

    /**
     * @param $type
     * @return bool
     */
    protected function isFatal($type): bool
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }

    /**
     * @param $message
     * @param int $code
     * @return void
     */
    protected function report($message, int $code): void
    {
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($code);

        print json_encode([
            'errors' => $message
        ], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);

        exit(0);
    }
}
