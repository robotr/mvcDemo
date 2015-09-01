<?php
/**
 * autoload.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * PHPs method to register as global spl_autoload-Handler
 * Grabs an instance of ServiceLocator then runs it
 * http://php.net/manual/en/language.oop5.autoload.php
 */
require 'library/SlickFW/Autoloader/ServiceLocator.php';
function class_autoload($class)
{
    $locator = SlickFW\Autoloader\ServiceLocator::getInstance();
    $locator->load($class);
}

spl_autoload_register('class_autoload', true, true);
