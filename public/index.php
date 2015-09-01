<?php
/**
 * index.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
(defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', 'local'));

chdir(dirname(__DIR__));

require 'autoload.php';

if (file_exists('config/application.config.php')) {
    $locator = SlickFW\Autoloader\ServiceLocator::getInstance();
    $config = require 'config/application.config.php';
    $locator->loadConfig($config);
    SlickFW\Mvc\ModuleSetup::getInstance('Website')->run();
} else {
    http_response_code('404');
    $prot = (!isset($_SERVER['SERVER_PROTOCOL'])) ? 'HTTP 1.0' :
        $_SERVER['SERVER_PROTOCOL'];
    header($prot . ' 404 Not Found');
    echo '<h1>The Requested Page could not be found!</h1>';
}
