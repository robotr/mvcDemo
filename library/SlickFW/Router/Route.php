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
 * original author Erik M Schulz
 */

namespace SlickFW\Router;

use SlickFW\Mvc\Controller\ControllerAbstract;

class Route
{
    /**
     * @param string $to
     * @param string $module
     * @param array $args
     * @return string|void
     * @throws \Exception
     */
    private static function execute($to, $module, $args)
    {
        $keys = explode('/', $to);
        $controller = $className = array_shift($keys);
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

        call_user_func($call, $values);

        $trX = $class->getResponse();
        $trX->setBody($class->view->render());
        if ($rqX->isDispatched()) {
            return '"' . $trX->send() . '"';
        } else {
            return $trX->getBody();
        }
    }

    /**
     * @param string $uri
     * @param array $mapping
     * @param string $module
     * @return null|string|void
     */
    public static function process($uri, $mapping, $module = 'default')
    {
        $port     = ((isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] !== '80') ?
            ':' . $_SERVER['SERVER_PORT'] : null);
        $scheme   = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http');
        $basePath = (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '/index.php') ?
            str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) : null;
        $uri = str_replace($basePath, '', $uri);
        $matched = false;
        foreach ($mapping as $from => $to) {
            if (empty($uri)) {
                $uri = '/';
            }
            if (preg_match($from, $uri, $args)) {
                $matched = true;
                try {
                    return self::execute($to, $module, $args);
                } catch (\Exception $e) {
                    if (function_exists('http_response_code')) {
                        http_response_code('404');
                    }
                    $prot = (!isset($_SERVER['SERVER_PROTOCOL'])) ? 'HTTP 1.0' : $_SERVER['SERVER_PROTOCOL'];
                    header($prot . ' 404 Not Found');
                    $message = '<h1>The Requested Page could not be found!</h1>';
                    if (defined('APPLICATION_ENV') && 'local' == APPLICATION_ENV) {
                        $message .= '<h2>' . $e->getMessage() .  '</h2><pre>' .
                            var_export($e, true) . PHP_EOL .
                            'FILE: ' . __FILE__ . ' - LINE: ' . __LINE__ . '</pre>';
                    }
                    $matched = false;
                }
            }
        }
        if ($matched) {
            return null;
        }

        if (isset($message)) {
            return $message;
        } else {
            // todo redirect!?
            header('Location: ' . $scheme . '://' . $_SERVER['SERVER_NAME'] . $port . $basePath);
            exit;
        }
    }
}