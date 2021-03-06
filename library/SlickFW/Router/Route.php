<?php
/**
 * Route.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Router\Route
 * A routing class for use with your controllers that doesn't use inheritance.
 *
 * original @author Erik M Schulz
 * @package SlickFW\Router
 */

namespace SlickFW\Router;

use SlickFW\Mvc\Controller\ControllerAbstract;

class Route
{
    /**
     * try matching the request-uri with any defined routes
     * @param $uri
     * @param $mapping
     * @param string $module
     */
    public static function match($uri, $mapping, $module = 'default')
    {
        $basePath = (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '/index.php') ?
            str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) : null;
        $uri = str_replace($basePath, '', $uri);
        if (empty($uri)) {
            $uri = '/';
        }
        $matched = false;
        foreach ($mapping as $from => $to) {
            $to['uri'] = $uri;
            /** @var RouteInterface $route */
            $route = $to['type'];
            $matched = $route::process($from, $to, $module);
            if ($matched) {
                self::execute($to['name'], $module, $matched);
                break;
            }
        }
        if (!$matched) {
            self::execute('Error/noroute', $module, []);
        }
    }

    /**
     * try to call the matched Routes' defined Controller/Action
     * @param string $to - combined definition of the rout to its controller/action
     * @param string $module - current module used for processing the route
     * @param array $args - possible additional arguments to pass to the specific route
     * @throws \Exception
     */
    public static function execute($to, $module, $args = [])
    {
        $keys = explode('/', $to);
        $controller = $className = array_shift($keys);
        if (':Controller' == $controller && isset($args[1])) {
            $controller = $className = ucfirst($args[1]);
        }
        $action = array_shift($keys);
        $className = $module . '\Controller\\' . $className;
        $class = null;
        $values = [];
        /** @var ControllerAbstract $class */
        // check if a controller-class exists
        if (class_exists($className)) {
            $class = new $className;
            if (count($keys)) {
                $values = array_slice($args, 1, count($keys));
                $values = array_combine($keys, $values);
            }
            if (':action' == $action && isset($args[1])) {
                $action = $args[1];
                if (empty($action)) {
                    $action = str_replace('/', '', $args[0]);
                }
            }
        } elseif (class_exists($module . '\Controller\Error')) {
            $className = $module . '\Controller\Error';
            $controller = 'Error';
            $action = 'nocontroller';
            $class = new $className;
        }
        // check if action-method exists
        if (method_exists($className, $action)) {
            $call = [$class, $action];
        }
        if (!isset($call) && class_exists($module . '\Controller\Error')) {
            $className = $module . '\Controller\Error';
            $controller = 'Error';
            $method = $action = 'noaction';
            $class = new $className;
            $call = [$class, $method];
        } elseif (!class_exists($module . '\Controller\Error')) {
            throw new \Exception('Method "' . $action . '" not found in Class "' . $className . '"!');
        }
        // apply the routes specific request-params
        $rqX = $class->getRequest();
        $rqX->setModule($module)->setController($controller)
            ->setAction($action)->setDispatched(true);
        $response = '';
        // process controller-class's action-method
        try {
            call_user_func($call, $values);
            $trX = $class->getResponse();
            $trX->setBody($class->view->render());
            if ($rqX->isDispatched()) {
                $response = $trX->send();
            } else {
                $response = $trX->getBody();
            }
        } catch (\Exception $e) {
            null;
        } finally {
            if (!empty($response) && !isset($e)) {
                echo $response;
            } elseif (isset($e) && 'Error/internal' !== $to) {
                // log Exception
                error_log($e);
                // try responding with error-page
                self::execute('Error/internal', $module, []);
            }
        }
    }
}
