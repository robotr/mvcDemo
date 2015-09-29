<?php
/**
 * Simple.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Router\Route\Simple
 *
 * @package SlickFW\Router\Route
 */

namespace SlickFW\Router\Route;

use SlickFW\Router\RouteInterface;

class Simple implements RouteInterface
{
    static function process($from, $to, $module)
    {
        if ($from == $to['uri']) {
            return true;
        } else {
            return false;
        }
    }
}
