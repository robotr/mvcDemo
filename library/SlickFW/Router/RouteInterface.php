<?php
/**
 * RouteInterface.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Router\RouteInterface
 *
 * @package SlickFW\Router
 */

namespace SlickFW\Router;

interface RouteInterface
{
    /**
     * match the requested URI against a defined route of a module
     * @param string $from
     * @param array $to
     * @param string $module
     * @return array|bool
     */
    static function process($from, $to, $module);
}