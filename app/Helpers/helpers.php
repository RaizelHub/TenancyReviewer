<?php

if (!function_exists('log_info')) {
    /**
     * Log an informational message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    function log_info($message, array $context = [])
    {
        app('log')->info($message, $context);
    }
}

if (!function_exists('log_error')) {
    /**
     * Log an error message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    function log_error($message, array $context = [])
    {
        app('log')->error($message, $context);
    }
}

if (!function_exists('log_warning')) {
    /**
     * Log a warning message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    function log_warning($message, array $context = [])
    {
        app('log')->warning($message, $context);
    }
}

if (!function_exists('log_debug')) {
    /**
     * Log a debug message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    function log_debug($message, array $context = [])
    {
        app('log')->debug($message, $context);
    }
}

if (!function_exists('route_has')) {
    /**
     * Check if a route exists.
     *
     * @param string $name
     * @return bool
     */
    function route_has($name)
    {
        return app('router')->has($name);
    }
}

if (!function_exists('storage_disk')) {
    /**
     * Get a storage disk instance.
     *
     * @param string $disk
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    function storage_disk($disk)
    {
        return app('filesystem')->disk($disk);
    }
}

if (!function_exists('tenant')) {
    /**
     * Get the current tenant.
     *
     * @return mixed
     */
    function tenant()
    {
        return app()->bound('tenant') ? app('tenant') : null;
    }
}

if (!function_exists('get_facades')) {
    /**
     * Get the Facades helper class.
     *
     * @return \App\Helpers\Facades
     */
    function get_facades()
    {
        return new \App\Helpers\Facades();
    }
}

// Add global Log class that proxies to the Laravel Log facade
class Log
{
    /**
     * Log an informational message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info($message, array $context = [])
    {
        app('log')->info($message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error($message, array $context = [])
    {
        app('log')->error($message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning($message, array $context = [])
    {
        app('log')->warning($message, $context);
    }

    /**
     * Log a debug message.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function debug($message, array $context = [])
    {
        app('log')->debug($message, $context);
    }
}



// Add global Auth class that proxies to the Laravel Auth facade
class Auth
{
    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function user()
    {
        return app('auth')->user();
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public static function check()
    {
        return app('auth')->check();
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public static function guest()
    {
        return app('auth')->guest();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public static function id()
    {
        return app('auth')->id();
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public static function attempt(array $credentials = [], $remember = false)
    {
        return app('auth')->attempt($credentials, $remember);
    }

    /**
     * Log a user into the application.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param bool $remember
     * @return void
     */
    public static function login($user, $remember = false)
    {
        app('auth')->login($user, $remember);
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public static function logout()
    {
        app('auth')->logout();
    }

    /**
     * Get the Auth guard instance.
     *
     * @param string|null $guard
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public static function guard($guard = null)
    {
        return app('auth')->guard($guard);
    }
}

// Add global Validator class that proxies to the Laravel Validator facade
class Validator
{
    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return app('validator')->make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Validate the given data against the provided rules.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     */
    public static function validate(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return app('validator')->validate($data, $rules, $messages, $customAttributes);
    }

    /**
     * Register a custom validator extension.
     *
     * @param string $rule
     * @param \Closure|string $extension
     * @param string|null $message
     * @return void
     */
    public static function extend($rule, $extension, $message = null)
    {
        app('validator')->extend($rule, $extension, $message);
    }
}
