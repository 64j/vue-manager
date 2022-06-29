<?php

declare(strict_types=1);

namespace VueManager\Exception;

use Exception;

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
        $code = $this->defaultCode;
        $message = $message ?: $this->defaultMessage;

        $this->report([
            'line' => $line,
            'file' => $file,
            'code' => $code,
            'message' => $message,
        ], $code);
    }

    /**
     * @param \Exception $e
     * @return void
     */
    protected function handleException(Exception $e): void
    {
        $code = $e->getCode() ?: $this->defaultCode;
        $message = $e->getMessage() ?: $this->defaultMessage;

        $this->report([
            'code' => $code,
            'message' => $message,
        ], $code);
    }

    protected function handleShutdown()
    {
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
