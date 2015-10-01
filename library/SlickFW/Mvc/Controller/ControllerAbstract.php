<?php
/**
 * ControllerAbstract.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Mvc\ControllerAbstract
 * the abstract Controller-Class that all individual Controllers have to inherit
 * @package Mvc\Controller
 */

namespace SlickFW\Mvc\Controller;

use SlickFW\Mvc;
use SlickFW\Response;
use SlickFW\Request;

abstract class ControllerAbstract
{
    /**
     * @var Mvc\View
     */
    public $view;

    /**
     * @var Response\ResponseAbstract
     */
    protected $_response;

    /**
     * @var Request\RequestAbstract
     */
    protected $_request;

    /**
     * @var Mvc\Model\ViewContainer
     */
    protected $_model;

    /**
     * ctor-function to initialize basic controller behaviour
     */
    public function __construct()
    {
        $server = php_sapi_name();
        if ('cli' !== $server) {
            $this->_request  = new Request\Http();
            $this->_response = new Response\Http($this->_request);
        }
        $className = get_class($this);
        $classArray = explode('\\', $className);
        $module = array_shift($classArray);
        $this->view = Mvc\ModuleSetup::getInstance($module)->get(['View' => 'config']);
        $this->view->setRequest($this->getRequest());
        $this->_model = new Mvc\Model\ViewContainer($this->view);
        $this->init();
    }

    /**
     * @return Request\RequestAbstract|Request\Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return Response\Http
     */
    public function getResponse()
    {
        if ($this->_request->isDispatched() && $this->_request->isAjax()) {
            $this->_response->setContentType('application/json, text/xml, text/html; charset=UTF-8');
        }
        return $this->_response;
    }

    /**
     * method for setup-purposes in concrete controllers
     */
    public function init()
    {}
}