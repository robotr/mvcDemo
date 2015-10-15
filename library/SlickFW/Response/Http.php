<?php
/**
 * Http.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * Http
 * @package SlickFW\Response
 */

namespace SlickFW\Response;

use SlickFW\Request\RequestAbstract;

class Http extends ResponseAbstract
{
    /**
     * @var array
     */
    protected $_headers = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestAbstract $request)
    {
        parent::__construct($request);
        $this->_headers['Content-Type'] = 'text/html; charset=UTF-8';
    }

    /**
     * set a Response-Header, by default replaces any previous occurence of a header if $replace is not set to true
     * @param string $type
     * @param string $value
     * @param bool $replace
     * @return $this
     */
    public function setHeader($type, $value, $replace = false)
    {
        if (isset($this->_headers[$type]) && !empty($value)) {
            if ($replace) {
                $this->_headers[$type] = $value;
            } else {
                $this->_headers[$type] .= $value;
            }
        }
        return $this;
    }

    /**
     * unset all previously set Headers
     * @return $this
     */
    public function resetHeaders()
    {
        $this->_headers = [];
        return $this;
    }

    /**
     * return/send response-body
     * @return string
     */
    public function send()
    {
        foreach ($this->_headers as $type => $content) {
            header($type . ': ' . $content);
        }

        return $this->_body;
    }
}