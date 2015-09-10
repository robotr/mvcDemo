<?php
/**
 * View.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * View
 * class for generating output of any invoked controller-actions or
 * custom templates representing data or html (f.i. ViewHelpers)
 *
 * @package SlickFW\Mvc\View
 */

namespace SlickFW\Mvc;

use SlickFW\Request\RequestAbstract;

class View
{
    /**
     * constant definition for default placeholder value
     */
    const PLACEHOLDER = 'content';

    /**
     * constant definition for default layout-script value
     */
    const DEFAULTLAYOUT = 'layout';

    /**
     * @var string
     */
    protected $_defaultPlaceholder;

    /**
     * @var string
     */
    protected $_defaultLayout;

    /**
     * @var string
     */
    protected $_scriptPath;

    /**
     * @var string
     */
    protected $_scriptSuffix;

    /**
     * @var \SlickFW\Request\RequestAbstract
     */
    protected $_request;

    /**
     * @var array
     */
    protected $_filter;

    /**
     * @var bool
     */
    protected $_useViewStream = false;

    /**
     * @var bool
     */
    protected $_useStreamWrapper = false;

    /**
     * @var Collection
     */
    protected $_parts;

    /**
     * ctor for the view-object takes config-parameters (from a Controller) initialized in module-setup
     * @param array $options
     */
    public function __construct($options = array())
    {
        // enable use of stream wrapper if short-tags are used/supported
        $this->_useViewStream = (bool) ini_get('short_open_tag') ? false : true;

        // check setting to force usage of stream wrapper, enabling short-tags usage even if disabled
        if (array_key_exists('useStreamWrapper', $options)) {
            $this->_useStreamWrapper = (bool) $options['useStreamWrapper'];
            unset($options['useStreamWrapper']);
        }

        // set if custom stream-wrapper should be used
        if ($this->_useViewStream && $this->_useStreamWrapper) {
            if (!$this->hasStreamWrapper()) {
                stream_wrapper_register('slick.view', 'SlickFW\Mvc\View\Stream');
            }
        }

        // add filters to use somewhere upon setting public members that are passed through to the output
        if (isset($options['filters'])) {
            $this->_filter = $options['filters'];
        } else {
            $this->_filter = array();
        }

        // walk through options-array to assign further settings
        $props = get_object_vars($this);
        foreach ($options as $key => $value) {
            if (array_key_exists('_' . $key, $props)) {
                $this->{'_' . $key} = $value;
            }
        }
    }

    /**
     * render the output
     * @return string
     */
    public function render()
    {
        if ($this->hasLayout()) {
            $this->renderLayout();
        } else {
            ob_start();
            $this->renderScript();
        }
        return ob_get_clean();
    }

    /**
     * render action-script assigned to this request to the current placeholder
     */
    public function renderToPlaceholder()
    {
        if ($this->_hasScript()) {
            if (isset($this->{$this->_defaultPlaceholder}) && !empty($this->{$this->_defaultPlaceholder})) {
                foreach ($this->{$this->_defaultPlaceholder} as $key => $value) {
                    $this->{$key} = $value;
                }
            }
            ob_start();
            $this->renderScript();
            $this->{$this->_defaultPlaceholder} = ob_get_clean();;
        } else {
            $this->{$this->_defaultPlaceholder} = '';
        }
    }

    /**
     * run a single action script
     */
    public function renderScript()
    {
        if ($this->_hasScript()) {
            $this->_run($this->_scriptPath . $this->_request->getController() .
                DIRECTORY_SEPARATOR . $this->_request->getAction() . $this->_scriptSuffix);
        }
    }

    /**
     * run the layout-script
     */
    public function renderLayout()
    {
        $this->renderToPlaceholder();
        ob_start();
        $this->_run($this->_defaultLayout);
    }

    /**
     * whether or not this instance uses a layout template
     * @return bool
     */
    public function hasLayout()
    {
        return (isset($this->_defaultLayout) && isset($this->_scriptSuffix)
            && (file_exists($this->_defaultLayout . self::DEFAULTLAYOUT . $this->_scriptSuffix)
            || file_exists($this->_defaultLayout . $this->_scriptSuffix)));
    }

    /**
     * check for custom stream-wrapper in use
     * @return bool
     */
    public function hasStreamWrapper()
    {
        return (in_array('slick.view', stream_get_wrappers()));
    }

    /**
     * disable this instances layout
     */
    public function noLayout()
    {
        $this->_defaultLayout = null;
    }

    /**
     * @return bool
     */
    function useStreamWrapper()
    {
        return $this->_useStreamWrapper;
    }

    /**
     * method overload: magic setter-method for value assignment of public(dynamic) properties
     * @param string $prop
     * @param mixed $value
     */
    public function __set($prop, $value)
    {
        $prop = lcfirst(str_replace('_', '', $prop));
        if ($this->_defaultPlaceholder !== $prop) {
            $this->{$prop} = $this->_filter($value);
        } else {
            $this->_setContent($value);
        }
    }

    /**
     * method overload: __call-method to handle all set-calls whilst
     * cleaning up the $method-name to prevent setting/executing protected or private members/methods
     * @param string $method
     * @param mixed $params
     * @return mixed|\SlickFW\Mvc\View
     */
    public function __call($method, $params)
    {
        $method = str_replace('_', '', (string)$method);
        $return = $this;
        if (!method_exists($this, $method)
            && (substr($method, 0, 3) == 'set') || substr($method, 0, 3) == 'get'
        ) {
            // propget/propset
            $io = substr($method, 0, 3);
            $prop = strtolower(substr($method, 3, strlen($method) - 2));
            switch ($io) {
                case 'get':
                    if ('placeholder' == $prop) {
                        $return = $this->_getPlaceholder();
                        break;
                    }
                    $return = (isset($this->{$prop})) ? $this->{$prop} : null;
                    break;
                case 'set':
                    if (isset($params)) {
                        if (count($params) == 1) {
                            if (is_string($params[0])) {
                                $this->{$prop} = $params[0];
                            } elseif (is_array($params[0])) {
                                foreach ($params as $key => $value) {
                                    $this->{$prop}->$key = $value;
                                }
                            }
                        }
                    }
                    break;
                default:
                    // nothing
                    break;
            }
        } elseif (method_exists($this, $method)) {
            return call_user_func(array($this, $method), $params);
        } else {
            // call shortcut methods / instantiate view-part class
            $file = dirname(__DIR__) . '/Mvc/View/Part/' . ucfirst($method) . '.php';
            if (!isset($this->_parts[$method]) && file_exists($file)) {
                $partClass = 'SlickFW\Mvc\View\Part\\' . ucfirst($method);
                $this->_parts[$method] = new $partClass($this);
            }
            $return = $this->_parts[$method];
        }
        return $return;
    }

    /**
     * @param RequestAbstract $request
     * @return $this
     */
    public function setRequest($request)
    {
        if (($request instanceof RequestAbstract)) {
            $this->_request = $request;
        }
        return $this;
    }

    /**
     * @param $placeholder
     */
    public function setDefaultPlaceholder($placeholder = '')
    {
        if (!empty($placeholder)) {
            $this->_defaultPlaceholder = $placeholder;
        } else {
            $this->_defaultPlaceholder = self::PLACEHOLDER;
        }
    }

    /**
     * use alternate layout-script
     * @param string $name
     */
    public function setLayout($name = '')
    {
        if (!empty($this->_defaultLayout) && !empty($name)
            && (file_exists($name) || file_exists($name . $this->_scriptSuffix))
        ) {
            $this->_defaultLayout = $name;
        }
    }

    /**
     * @return string
     */
    protected function _getPlaceholder()
    {
        return $this->_defaultPlaceholder;
    }

    /**
     * @param mixed $value
     */
    protected function _setContent($value)
    {
        if (!isset($this->{$this->_defaultPlaceholder}) && ($value instanceof \ArrayObject || is_array($value))) {
            $this->{$this->_defaultPlaceholder} = $value;
        } elseif (isset($this->{$this->_defaultPlaceholder})
            && $this->{$this->_defaultPlaceholder} instanceof \ArrayObject && is_array($value)
        ) {
            foreach ($value as $offset => $oVal) {
                $this->{$this->_defaultPlaceholder}->offsetSet($offset, $oVal);
            }
        } elseif (is_string($value)) {
            $this->{$this->_defaultPlaceholder} = $value;
        }
    }

    /**
     * includes the template script in the current rendering process with only public view-variables to display
     * *NOTICE: dynamic parameter which is fetched via func_get_arg(0)*
     * @param string {} The view script to execute.
     */
    protected function _run()
    {
        $file = func_get_arg(0);
        if (false == strpos($file, self::DEFAULTLAYOUT)
            && file_exists($file . self::DEFAULTLAYOUT . $this->_scriptSuffix)
        ) {
            $file .= self::DEFAULTLAYOUT . $this->_scriptSuffix;
        } elseif (file_exists($file . $this->_scriptSuffix)) {
            $file .= $this->_scriptSuffix;
        }
        if ($this->hasStreamWrapper()) {
            include 'slick.view://' . $file;
        } else {
            include $file;
        }
    }

    /**
     * @return bool
     */
    private function _hasScript()
    {
        $scriptFile = $this->_scriptPath . $this->_request->getController() .
            DIRECTORY_SEPARATOR . $this->_request->getAction() . $this->_scriptSuffix;
        return (isset($this->_scriptPath) && isset($this->_scriptSuffix) && file_exists($scriptFile));
    }

    /**
     * sets whether or not to use the custom stream-wrapper
     *
     * @param bool $flag
     * @return $this
     */
    private function _setUseStreamWrapper($flag)
    {
        $this->_useStreamWrapper = (bool) $flag;
        return $this;
    }

    /**
     * @todo implement filters and method
     * @param mixed $in input value to filter
     * @return mixed the filtered input-value
     */
    private function _filter($in)
    {
        return $in;
        $out = '';
        return $out;
    }

}
