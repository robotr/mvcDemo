<?php
/**
 * ModuleSetup.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * ModuleSetup
 * @package SlickFW\Mvc
 */

namespace SlickFW\Mvc;

use SlickFW\Error\Handler;
use SlickFW\Mvc\Model\ServiceRegister;
use SlickFW\Router\Listener;

abstract class ModuleSetup implements ModuleInterface
{
    /**
     * @var string
     */
    protected $_moduleName;

    /**
     * @var ModuleInterface
     */
    protected static $_instance;

    /**
     * @var string
     */
    protected static $_appConfigPath = 'config/application.config.php';

    /**
     * @var ServiceRegister
     */
    protected $_serviceRegister;

    /**
     * @var string
     */
    protected $_defaultModule = false;

    /**
     * @var Handler
     */
    protected $_handler;

    /**
     * ctor
     * @param ServiceRegister $services
     */
    public function __construct(ServiceRegister $services)
    {
        $this->_serviceRegister = $services;
        $this->_initModule();
        $this->_initRoutes();
    }

    /**
     * initialize the module or return existing instance
     * @param string $module - name of the module to set-up
     * @return $this|ModuleInterface
     */
    public static function getInstance($module)
    {
        if (isset(self::$_instance)) {
            return self::$_instance;
        } else {
            $applicationConfig = [];
            if (file_exists(self::$_appConfigPath)) {
                $applicationConfig = require self::$_appConfigPath;
            }
            if (isset($module) && file_exists('module/' . $module . '/Config/module.config.php')) {
                $moduleConfig = require 'module/' . $module . '/Config/module.config.php';
                $applicationConfig = array_merge($applicationConfig, $moduleConfig);
            }
            return self::_init($applicationConfig);
        }
    }

    public function run()
    {
        $class = explode('\\', get_class($this));
        $module = array_shift($class);
        Listener::route($module);
    }

    /**
     * accessor for registered module-services and other general settings
     * @param string|array $service
     * @throws \Exception
     * @return mixed|null
     */
    public function get($service)
    {
        if ($this->_serviceRegister->isRegistered($service)) {
            try {
                return $this->_serviceRegister->getService($service);
            } catch(\Exception $e) {
                throw $e;
            }
        }
        return null;
    }

    /**
     * whether or not the current instance is the default module
     * @return bool
     */
    public function isDefaultModule()
    {
        return ($this->_defaultModule !== false);
    }

    /**
     * initialize Application by registering all configured services and setting the current module instance
     * @param array $config
     * @return $this|ModuleInterface
     */
    protected static function _init($config = [])
    {
        $serviceRegister = new ServiceRegister($config);
        foreach ($config as $key => $value) {
            $serviceRegister->add([$key => $value]);
        }
        self::$_instance = $serviceRegister->getService('Application');
        return self::$_instance;
    }

    /**
     * initialize module-configuration
     * @uses ModuleSetup::_setupErrorhandler()
     * @uses ModuleSetup::_setupDefaultmodule()
     */
    protected function _initModule()
    {
        foreach ($this->_serviceRegister->toArray() as $type => $tValues) {
            $setupMethod = '_setup' . ucfirst($type);
            if (method_exists($this, $setupMethod)) {
                call_user_func([$this, $setupMethod], $tValues);
            }
        }
    }

    /**
     * apply configured routes
     * @uses Listener
     */
    protected function _initRoutes()
    {
        foreach ($this->_serviceRegister->getService('routes') as $regxQuery => $path) {
            Listener::add($this->_defaultModule, $regxQuery, $path);
        }
    }

    /**
     * apply callback from configured class to set custom error-handler
     */
    private function _setupErrorhandler()
    {
        if (!isset($this->_handler)) {
            $errorHandler = $this->_serviceRegister->getService(['Errorhandler' => 'config']);
            if (!is_null($errorHandler) && method_exists($errorHandler, 'initHandler')) {
                $this->_handler = call_user_func([$errorHandler, 'initHandler']);
            }
        }
    }

    /**
     * apply whether the module is the default or not
     */
    private function _setupDefaultmodule()
    {
        if ($this->_serviceRegister->isRegistered('defaultmodule')) {
            $this->_defaultModule = $this->_serviceRegister->getService('defaultmodule');
        } else {
            $this->_defaultModule = false;
        }
    }

}
