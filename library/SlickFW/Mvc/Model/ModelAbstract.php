<?php
/**
 * ModelAbstract.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * ModelAbstract
 * @package SlickFW\Mvc\Model
 */

namespace SlickFW\Mvc\Model;

abstract class ModelAbstract
{
    /**
     * @var \ArrayObject
     */
    protected $_data;

    /**
     * init class and setup the internal $_data-ArrayObject
     */
    public function __construct()
    {
        $this->_data = new \ArrayObject();
    }

    /**
     * append data to the internal storage
     * @param mixed $data
     */
    public function add($data)
    {
        if (is_array($data) && !is_int(key($data))) {
            foreach ($data as $name => $value) {
                $this->_data->offsetSet($name, $value);
            }
        } else {
            $this->_data->append($data);
        }
    }

    /**
     * resets te internal storage and returns the old values if needed
     * @return array
     */
    public function reset()
    {
        return $this->_data->exchangeArray(new \ArrayObject());
    }

    /**
     * overwrite the internal data-storage
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data->exchangeArray($data);
    }

    /**
     * retrieve the data-storage
     * @param bool $arrayCopy
     * @return array|\ArrayObject
     */
    protected function getData($arrayCopy = false)
    {
        if ($arrayCopy) {
            return $this->_data->getArrayCopy();
        } else {
            return $this->_data;
        }
    }
}
