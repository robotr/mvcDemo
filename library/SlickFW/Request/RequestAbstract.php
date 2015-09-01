<?php
/**
 * RequestAbstract.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * RequestAbstract
 * @package SlickFW\Request
 */

namespace SlickFW\Request;

class RequestAbstract implements \ArrayAccess
{
    /**
     * @param mixed $rqValue
     * offset to check for
     * @return boolean true on success or false on failure
     * return value will be casted to boolean if non-boolean was returned
     */
    public function offsetExists($rqValue)
    {
    }

    /**
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
    }

    /**
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
    }
}
