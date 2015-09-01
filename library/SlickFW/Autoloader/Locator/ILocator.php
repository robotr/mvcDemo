<?php
/**
 * Interface.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Autoloader\Locator\Interface
 * Defines the methods any actual locators must implement
 *
 * http://php.net/manual/en/language.oop5.autoload.php
 * @package Autoloader
 * @author Chris Corbyn
 */

namespace SlickFW\Autoloader\Locator;

interface ILocator
{
    /**
     * Inform of whether or not the given class can be found
     * @param string $class
     * @return bool
     */
    public function canLocate($class);
    /**
     * Get the path to the class
     * @param string $class
     * @return string
     */
    public function getPath($class);
}