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
 */

namespace SlickFW\Router;

use SlickFW\Mvc\Controller\ControllerAbstract;

class Route
{

    /**
     * match the requested URI against the defined set of routes for this module
     * @param string $uri
     * @param array $mapping
     * @param string $module
     */
    public static function process($uri, $mapping, $module = 'default')
    {
        $basePath = (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '/index.php') ?
            str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) : null;
        $uri = str_replace($basePath, '', $uri);
        if (empty($uri)) {
            $uri = '/';
        }
        $matched = false;
        foreach ($mapping as $from => $to) {
            if (preg_match($from, $uri, $args)) {
                self::execute($to, $module, $args);
                $matched = true;
                break;
            }
        }
        if (!$matched) {
            self::execute('Error/noroute', $module, array());
        }
    }

    /**
     * try to call the matched Routes defined Controller/Action
     * @param string $to
     * @param string $module
     * @param array $args
     * @throws \Exception
     */
    private static function execute($to, $module, $args)
    {
        $keys = explode('/', $to);
        $controller = $className = array_shift($keys);
        if (':Controller' == $controller && isset($args[1])) {
            $controller = $className = ucfirst($args[1]);
        }
        $action = array_shift($keys);
        $className = $module . '\Controller\\' . $className;
        $class = null;
        $values = array();
        /** @var ControllerAbstract $class */
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

        if (method_exists($className, $action)) {
            $call = array($class, $action);
        }

        if (!isset($call) && class_exists($module . '\Controller\Error')) {
            $className = $module . '\Controller\Error';
            $controller = 'Error';
            $method = $action = 'noaction';
            $class = new $className;
            $call = array($class, $method);
        } elseif (!class_exists($module . '\Controller\Error')) {
            throw new \Exception('Method "' . $action . '" not found in Class "' . $className . '"!');
        }

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
            throw $e;
        } finally {
            if (!empty($response) && !isset($e)) {
                echo $response;
            } elseif (isset($e) && 'Error/internal' !== $to) {
                // try responding with error-page
                Route::execute('Error/internal', $module, array());
            }
        }
    }

}
