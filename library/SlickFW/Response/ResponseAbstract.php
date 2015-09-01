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
     * @var array
     */
    protected $_headers;

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
        $this->_headers = array();
    }

    /**
     * set content-type header
     * @param string $type
     */
    public function setContentType($type)
    {
        if (!empty($type)) {
            $type = trim(strip_tags($type));
        } else {
            $type = 'text/html; charset=UTF-8';
        }
        $this->_headers += array('Content-Type' => $type);
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

    /**
     * flush output-buffer/send response-body/
     * @todo add http-headers
     */
    public function send()
    {
        foreach ($this->_headers as $header => $type) {

        }

        echo $this->_body;
    }

}
