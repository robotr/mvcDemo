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

use SlickFW\Mvc\Model\ServiceRegister;
use SlickFW\Router\Listener;

abstract class ModuleSetup implements ModuleInterface
{
    protected $_moduleName;

    /**
     * @var ModuleInterface
     */
    protected static $_instance;

    /**
     * @var ServiceRegister
     */
    protected $_serviceRegister;

    /**
     * @var string
     */
    protected $_defaultModule = false;

    /**
     * ctor
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
            $applicationConfig = array();
            if (file_exists('config/application.config.php')) {
                $applicationConfig = require 'config/application.config.php';
            }
            if (isset($module) && file_exists('module/' . $module . '/Config/module.config.php')) {
                $moduleConfig = require 'module/' . $module . '/Config/module.config.php';
                $applicationConfig = array_merge($applicationConfig, $moduleConfig);
            }
            return self::_init($applicationConfig);
        }
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
    protected static function _init($config = array())
    {
        $serviceRegister = new ServiceRegister($config);
        foreach ($config as $key => $value) {
            $serviceRegister->add(array($key => $value));
        }
        self::$_instance = $serviceRegister->getService('Application');
        return self::$_instance;
    }

    /**
     * initialize module-configuration
     * @uses Setup::_setupDatabase()
     */
    protected function _initModule()
    {
        foreach ($this->_serviceRegister->toArray() as $type => $tValues) {
            $setupMethod = '_setup' . ucfirst($type);
            if (method_exists($this, $setupMethod)) {
                call_user_func(array($this, $setupMethod), $tValues);
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
        $errorHandler = $this->_serviceRegister->getService(array('Errorhandler' => 'config'));
        if (!is_null($errorHandler) && method_exists($errorHandler, 'initHandler')) {
            call_user_func(array($errorHandler, 'initHandler'));
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
