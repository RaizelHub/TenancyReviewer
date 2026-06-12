<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log as LaravelLog;

/**
 * Helper functions for logging operations
 */
class LogHelper
{
    /**
     * Log an informational message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        LaravelLog::info($message, $context);
    }

    /**
     * Log an error message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        LaravelLog::error($message, $context);
    }

    /**
     * Log a warning message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        LaravelLog::warning($message, $context);
    }

    /**
     * Log a debug message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        LaravelLog::debug($message, $context);
    }

    /**
     * Log a critical message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function critical(string $message, array $context = []): void
    {
        LaravelLog::critical($message, $context);
    }
}
