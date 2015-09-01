<?php
/**
 * Listener.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Router\Listener
 *
 * @package SlickFW\Router
 */

namespace SlickFW\Router;

class Listener
{
    /**
     * from modules' init-class assigned routes
     * @var array
     */
    public static $routes = array();

    /**
     * @param string $module
     * @param $route
     * @param string $path
     * @param bool $overwrite
     * @internal param string $routeName
     */
    public static function add($module, $route, $path, $overwrite = false)
    {
        if (!array_key_exists($module, self::$routes) || !isset(self::$routes[$module][$route])) {
            static::$routes[$module][$route] = $path;
        } elseif (isset(self::$routes[$module][$route]) && $overwrite) {
            static::$routes[$module][$route] = $path;
        }
    }

    /**
     * @param string $module
     */
    public static function route($module)
    {
        if (array_key_exists('REQUEST_URI', $_SERVER) && array_key_exists('QUERY_STRING', $_SERVER)) {
            $uri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
            if (!empty($uri) && isset(self::$routes[$module])) {
                try {
                    Route::process(urldecode($uri), self::$routes[$module], $module);
                } catch(\Exception $e) {
                    trigger_error('Exception with message: ' . PHP_EOL .
                        '"' . $e->getMessage() . '"' . PHP_EOL .
                        'Stack-Trace:' . PHP_EOL . '  ' . str_replace(PHP_EOL, PHP_EOL . '  ', $e->getTraceAsString()),
                        E_USER_ERROR);
                }
            }
        }
    }

}
