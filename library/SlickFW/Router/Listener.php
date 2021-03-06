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
     * from modules' setup-class assigned routes
     * @var array
     */
    public static $routes = [];

    /**
     * add routes from config f.i. through \SlickFW\Mvc\ModuleSetup::_initRoutes()
     * @param string $module
     * @param string $route
     * @param string $path
     * @param bool $overwrite
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
     * @throws \Exception
     */
    public static function route($module)
    {
        if (array_key_exists('REQUEST_URI', $_SERVER) && array_key_exists('QUERY_STRING', $_SERVER)) {
            $uri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
            if (!empty($uri) && isset(self::$routes[$module])) {
                Route::match(urldecode($uri), self::$routes[$module], $module);
            }
        }
    }

}
