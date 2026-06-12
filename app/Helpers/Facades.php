<?php

namespace App\Helpers;

/**
 * Global facade accessor class to provide access to Laravel facades
 * This helps prevent "Class not found" errors when using facades directly
 */
class Facades
{
    /**
     * Get the Log facade instance
     *
     * @return \Illuminate\Log\LogManager
     */
    public static function log()
    {
        return app('log');
    }

    /**
     * Get the Route facade instance
     *
     * @return \Illuminate\Routing\Router
     */
    public static function route()
    {
        return app('router');
    }

    /**
     * Get the Storage facade instance
     *
     * @return \Illuminate\Filesystem\FilesystemManager
     */
    public static function storage()
    {
        return app('filesystem');
    }

    /**
     * Get the Auth facade instance
     *
     * @return \Illuminate\Auth\AuthManager
     */
    public static function auth()
    {
        return app('auth');
    }

    /**
     * Get the Session facade instance
     *
     * @return \Illuminate\Session\SessionManager
     */
    public static function session()
    {
        return app('session');
    }

    /**
     * Get the Cache facade instance
     *
     * @return \Illuminate\Cache\CacheManager
     */
    public static function cache()
    {
        return app('cache');
    }

    /**
     * Get the DB facade instance
     *
     * @return \Illuminate\Database\DatabaseManager
     */
    public static function db()
    {
        return app('db');
    }

    /**
     * Get the Config facade instance
     *
     * @return \Illuminate\Config\Repository
     */
    public static function config()
    {
        return app('config');
    }

    /**
     * Get the URL facade instance
     *
     * @return \Illuminate\Routing\UrlGenerator
     */
    public static function url()
    {
        return app('url');
    }

    /**
     * Get the Validator facade instance
     *
     * @return \Illuminate\Validation\Factory
     */
    public static function validator()
    {
        return app('validator');
    }
}
