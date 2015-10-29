<?php
/**
 * module.config.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
return [
    'Application' => [
        'class' => 'Website\Setup'
    ],
    'Errorhandler' => [
        'class' => 'SlickFW\Error\Handler',
        'config' => [
            'log_level' => error_reporting(),
            'log_dir'   => 'log'
        ],
    ],
    'Logger' => [
        'class'  => 'SlickFW\Error\Logger',
        'file' => [
            'log_dir' => 'log'
        ],
        'database' => [],
        'mail' => []
    ],
    'Database' => [
        'class'   => 'SlickFW\Service\Db',
        'default' => [
            'dbtype'  => 'mysql',
            'host'    => '127.0.0.1',
            'user'    => 'root',
            'pass'    => 'password',
            'dbname'  => 'world',
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ],
        'other' => [
            'dbtype'  => 'mysql',
            'host'    => '10.0.2.2',
            'user'    => 'root',
            'pass'    => 'password',
            'dbname'  => 'albums',
            'options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ]
    ],
    'View' => [
        'class'  => 'SlickFW\Mvc\View',
        'config' => [
            'defaultPlaceholder' => 'content',
            'defaultLayout'      => dirname(__DIR__) . '/View/scripts/',
            'scriptPath'         => dirname(__DIR__) . '/View/scripts/',
            'scriptSuffix'       => '.phtml',
            'useStreamWrapper'   => false,
        ]
    ],
    'routes' => [
        '#^/$#i' => [
            'name' => 'Index/index',
            'type' => 'SlickFW\Router\Route\Regex'],
        '#^/([a-zA-Z0-9]*)$#i' => [
            'name' => 'Index/:action',
            'type' => 'SlickFW\Router\Route\Regex'],
        '/imprint' => [
            'name' => 'Index/imprint',
            'type' => 'SlickFW\Router\Route\Simple'],
        '#^/error/([a-zA-Z0-9]*)$#i' => [
            'name' => 'Error/:action',
            'type' => 'SlickFW\Router\Route\Regex'],
        '#^/([a-zA-Z0-9]*)/(.*)$#i' => [
            'name' => ':Controller/:action',
            'type' => 'SlickFW\Router\Route\Regex'],
    ],
    'defaultmodule' => 'Website'
];