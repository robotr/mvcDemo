<?php
/**
 * ResponseAbstract.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * ResponseAbstract
 * @package SlickFW\Response
 */

namespace SlickFW\Response;

use SlickFW\Request\RequestAbstract;

class ResponseAbstract
{
    /**
     * @var mixed
     */
    protected $_body;

    /**
     * @var RequestAbstract
     */
    protected $_request;

    /**
     * setup response-handler
     * @param RequestAbstract $request
     */
    public function __construct(RequestAbstract $request)
    {
        $this->_request = $request;
    }

    /**
     * apply response-body
     * @param mixed $content
     */
    public function setBody($content)
    {
        $this->_body = $content;
    }

    /**
     * return the response-body instead of outputting it
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

}
