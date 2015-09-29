<?php
/**
 * Regex.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Router\Route\Regex
 *
 * @package SlickFW\Router\Route
 */

namespace SlickFW\Router\Route;

use SlickFW\Router\RouteInterface;

class Regex implements RouteInterface
{
    static function process($from, $to, $module)
    {
        if (preg_match($from, $to['uri'], $args)) {
            return $args;
        } else {
            return false;
        }
    }
}
