<?php
/**
 * module.config.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
return array(
    'Application' => array(
        'class' => 'Website\Setup'
    ),
    'Errorhandler' => array(
        'class' => 'SlickFW\Error\Handler',
        'config' => array(
            'log_level' => error_reporting(),
            'log_dir'   => 'log'
        ),
    ),
    'Logger' => array(
        'class'  => 'SlickFW\Error\Logger',
        'file' => array(
            'log_dir' => 'log'
        ),
        'database' => array(),
        'mail' => array()
    ),
    'Database' => array(
        'class'   => 'SlickFW\Service\Db',
        'default' => array(
            'dbtype'  => 'mysql',
            'host'    => '127.0.0.1',
            'user'    => 'root',
            'pass'    => 'password',
            'dbname'  => 'world',
            'options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY
            )
        ),
        'other' => array(
            'dbtype'  => 'mysql',
            'host'    => '10.0.2.2',
            'user'    => 'root',
            'pass'    => 'password',
            'dbname'  => 'albums',
            'options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY
            )
        )
    ),
    'View' => array(
        'class'  => 'SlickFW\Mvc\View',
        'config' => array(
            'defaultPlaceholder' => 'content',
            'defaultLayout'      => dirname(__DIR__) . '/View/scripts/',
            'scriptPath'         => dirname(__DIR__) . '/View/scripts/',
            'scriptSuffix'       => '.phtml',
            'useStreamWrapper'   => false,
        )
    ),
    'routes' => array(
        '#^/$#i'                     => array(
            'name' => 'Index/index',
            'type' => 'SlickFW\Router\Route\Regex'),
        '#^/([a-zA-Z0-9]*)$#i'       => array(
            'name' => 'Index/:action',
            'type' => 'SlickFW\Router\Route\Regex'),
        '/imprint'                   => array(
            'name' => 'Index/imprint',
            'type' => 'SlickFW\Router\Route\Simple'),
        '#^/error/([a-zA-Z0-9]*)$#i' => array(
            'name' => 'Error/:action',
            'type' => 'SlickFW\Router\Route\Regex'),
        '#^/([a-zA-Z0-9]*)/(.*)$#i'  => array(
            'name' => ':Controller/:action',
            'type' => 'SlickFW\Router\Route\Regex'),
    ),
    'defaultmodule' => 'Website'
);