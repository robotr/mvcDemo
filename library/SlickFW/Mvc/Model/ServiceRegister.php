<?php
/**
 * ServiceRegister.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * ServiceRegister
 * @package SlickFW\Mvc\Model
 */

namespace SlickFW\Mvc\Model;

class ServiceRegister extends ModelAbstract
{
    /**
     * collection of any previously called services
     * @var array
     */
    protected $_services = array();

    /**
     * check if a service has been registered
     * @param $service
     * @return bool
     */
    public function isRegistered($service)
    {
        if (!is_string($service) && is_array($service) && count($service) == 1) {
            foreach ($service as $name => $value) {
                return ($this->_data->offsetExists($name));
            }
        }
        return ($this->_data->offsetExists($service));
    }

    /**
     * request a service from the registry
     * @param $name
     * <p>single-element array index by the service-name and the value of a service-specific config<br>
     * or a string simply naming the desired service
     * </p>
     * @return null|mixed
     */
    public function getService($name)
    {
        $service = null;
        if ('Application' == $name) {
            if (isset($this->_services['Application'])) {
                $service = $this->_services['Application'];
            } elseif ($this->_data->offsetExists('Application')
                && isset($this->_data->offsetGet('Application')['class'])
            ) {
                $setup = $this->_data->offsetGet('Application')['class'];
                $service = new $setup($this);
            }
            $this->_services['Application'] = $service;
        } else {
            if (!is_string($name) && is_array($name) && count($name) == 1) {
                foreach ($name as $index => $value) {
                    $setup = $this->_data->offsetGet($index);
                }
            } else {
                $setup = $this->_data->offsetGet($name);
            }
            if (isset($setup) && isset($setup['class']) && class_exists($setup['class'])) {
                if (!isset($this->_services[$setup['class']])) {
                    if (isset($value) && isset($setup[$value])) {
                        $service = new $setup['class']($setup[$value]);
                    } else {
                        $service = new $setup['class'];
                    }
                    $this->_services[$setup['class']] = $service;
                } else {
                    $service = $this->_services[$setup['class']];
                }
            } elseif ((is_string($name) || is_int($name)) && $this->_data->offsetExists($name)) {
                $service = $this->_data->offsetGet($name);
            }
        }

        return $service;
    }

    /**
     * returns iterable instance of the ServiceRegisters' configuration
     * @return \ArrayIterator
     */
    public function toArray()
    {
        return $this->_data->getIterator();
    }

}