<?php
/**
 * Http.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Request\Http
 * @package Request
 */

namespace SlickFW\Request;

class Http extends RequestAbstract
{
    /**
     * @var \ArrayObject
     */
    protected $_post;

    /**
     * @var \ArrayObject
     */
    protected $_query;

    /**
     * @var \ArrayObject
     */
    protected $_request;

    /**
     * @var \ArrayObject
     */
    protected $_server;

    /**
     * @var string
     */
    protected $_module = null;

    /**
     * @var string
     */
    protected $_controller = null;

    /**
     * @var string
     */
    protected $_action = null;

    /**
     * @var bool
     */
    protected $_dispatched = false;

    /**
     * @return Http
     */
    public function __construct()
    {
        $this->_post    = new \ArrayObject((isset($_POST)) ? $_POST : []);
        $this->_query   = new \ArrayObject((isset($_GET)) ? $_GET : []);
        $this->_request = new \ArrayObject((isset($_REQUEST)) ? $_REQUEST : []);
        $this->_server  = new \ArrayObject((isset($_SERVER)) ? $_SERVER : []);
        return $this;
    }

    /* GETTER */

    /**
     * @return string the module name
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * @return string the controller name
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @return string the action name
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * return the global server array
     * @param string $key
     * @return string|\ArrayObject|null
     */
    public function getServer($key = '')
    {
        if (empty($key)) {
            return $this->_server;
        } elseif ($this->_server->offsetExists($key)) {
            return $this->_server[$key];
        }
        return null;
    }

    /**
     * returns the requests GET-parameters
     * @return \ArrayObject
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * returns the requests POST-Parameters
     * @return \ArrayObject
     */
    public function getPost()
    {
        return $this->_post;
    }

    /* SETTER */

    /**
     * sets the module name
     * @param string $name
     * @return $this
     */
    public function setModule($name = '')
    {
        if (!empty($name) && is_string($name)) {
            $this->_module = $name;
        }
        return $this;
    }

    /**
     * sets the controller name
     * @param string $name
     * @return $this
     */
    public function setController($name = '')
    {
        if (!empty($name) && is_string($name)) {
            $this->_controller = $name;
        }
        return $this;
    }

    /**
     * sets the action name
     * @param string $name
     * @return $this
     */
    public function setAction($name = '')
    {
        if (!empty($name) && is_string($name)) {
            $this->_action = $name;
        }
        return $this;
    }

    /**
     * set wether or not the request has been dispatched
     * @param bool $flag
     */
    public function setDispatched($flag = false)
    {
        $this->_dispatched = (is_bool($flag)) ? $flag : false;
    }

    /* EXTRAS */

    /**
     * perform AJAX check
     * @return bool
     */
    public function isAjax()
    {
        if ($this->_server->offsetExists('HTTP_X_REQUESTED_WITH')
            && strtolower($this->_server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            return true;
        }
        return false;
    }

    /**
     * whether or not this request was set to dispatched
     * @return bool
     */
    public function isDispatched()
    {
        return $this->_dispatched;
    }
}
