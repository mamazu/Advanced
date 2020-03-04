<?php
/**
 * 
 * Advanced microFramework
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * 
 * @copyright Copyright (c) 2019 Advanced microFramework
 * @author    Advanced microFramework Team (Denzel Code, Soull Darknezz)
 * @link https://github.com/DenzelCode/Advanced
 * 
 */

namespace advanced\session;

class SessionManager{

    /**
     * Init session.
     *
     * @return void
     */
    public static function init() : void {
        session_start();
    }
    
    /**
     * Get data feom session or cookie.
     *
     * @param string $name
     * @return mixed
     */
    public static function get(string $name) {
        if (!empty($_SESSION[$name])) return $_SESSION[$name]; else if (!empty($_COOKIE[$name])) return $_COOKIE[$name]; else return null;
    }

    /**
     * Set data to session/cookie.
     *
     * @param string $name
     * @param mixed $value
     * @param boolean $cookie
     * @param integer $time
     * @param string $directory
     * @return void
     */
    public static function set(string $name, $value, bool $cookie = false, int $time = 3600 * 24 * 365, string $directory = "/") : void {
        $_SESSION[$name] = $value;

        if ($cookie) setcookie($name, $value, time() + $time, $directory);
    }

    /**
     * Set data to session/cookie as array.
     *
     * @param array $sessions
     * @param boolean $cookie
     * @param integer $time
     * @param string $directory
     * @return void
     */
    public static function setByArray(array $sessions, bool $cookie = false, int $time = 3600 * 24 * 365, string $directory = "/") : void {
        foreach ($sessions as $key => $value) self::set($key, $value, $cookie, $time, $directory);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param string $directory
     * @return void
     */
    public static function delete(string $name, string $directory = "/") : void {
        unset($_SESSION[$name]);

        setcookie($name, false, time() - 1000, $directory);
    }

    public static function deleteByArray(array $sessions, string $directory = "/") : void {
        foreach ($sessions as $session) {
            unset($_SESSION[$session]);

            setcookie($session, false, time() - 1000, $directory);
        }
    }

    public static function destroy() : void {
        session_destroy();
    }
}

