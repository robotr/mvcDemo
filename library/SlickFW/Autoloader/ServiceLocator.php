<?php
/**
 * ServiceLocator.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Autoloader\ServiceLocator
 *
 * The main service locator.
 * Uses loosely coupled locators in order to operate
 *
 * @package Autoloader
 * @author Chris Corbyn
 * @see http://php.net/manual/en/language.oop5.autoload.php
 */

namespace SlickFW\Autoloader;

class ServiceLocator
{
    /**
     * Contains any attached service locators
     * @var array Locator
     */
    protected $_locators = [];

    /**
     * @var ServiceLocator
     */
    protected static $_instance;

    private function __construct()
    {
        return $this;
    }

    /**
     * Attach a new type of locator
     * @param \SlickFW\Autoloader\Locator\ILocator $locator
     * @param string $key
     */
    public function attachLocator(Locator\ILocator $locator, $key)
    {
        $this->_locators[$key] = $locator;
    }

    /**
     * Remove a locator that's been added
     * @param string $key
     * @return bool
     */
    public function dropLocator($key)
    {
        if (static::isActiveLocator($key)) {
            unset($this->_locators[$key]);
            return true;
        }
        return false;
    }

    /**
     * Check if a locator is currently loaded
     * @param string $key
     * @return bool
     */
    public function isActiveLocator($key)
    {
        return array_key_exists($key, $this->_locators);
    }

    /**
     * loads configuration values and attaches defined autoloading modules/libraries
     * @param array $config
     */
    public function loadConfig($config)
    {
        foreach ($config as $typeKey => $typeValues) {
            foreach ($typeValues as $key => $values) {
                if (!interface_exists('SlickFW\Autoloader\Locator\ILocator')) {
                    require dirname(__DIR__) . '/Autoloader/Locator/ILocator.php';
                }
                $locPath = $typeKey . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . $values['locator'] . '.php';
                if (!class_exists($values['locator']) && file_exists($locPath)) {
                    require_once $locPath;
                } elseif (!class_exists($values['locator'])
                    && file_exists(str_replace($key . DIRECTORY_SEPARATOR, '', $locPath))
                ) {
                    $locPath = str_replace($key . DIRECTORY_SEPARATOR, '', $locPath);
                    require_once $locPath;
                }
                try {
                    $locator = new $values['locator']($values['base_path'], $values['class_dir_separator']);
                    $this->attachLocator($locator, $key);
                } catch (\RuntimeException $e) {
                    error_log('Failed to initialize ServiceLocator "' . $values['locator'] . '" ' . PHP_EOL .
                        'Log: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Load in the required service by asking all service locators
     * @param string $class
     */
    public function load($class)
    {
        foreach ($this->_locators as $obj) {
            /** @var $obj \SlickFW\Autoloader\Locator\ILocator */
            if ($obj->canLocate($class)) {
                $path = $obj->getPath($class);
                require $path;
                if (class_exists($class)) {
                    return;
                }
            }
        }
    }

    /**
     * return Singleton instance if not initialized create one
     * @return ServiceLocator
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
