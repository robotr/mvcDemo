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
        $this->setContentType('text/html; charset=UTF-8');
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
        $this->_headers += ['Content-Type' => $type];
    }

    /**
     * flush output-buffer/send response-body/
     * @todo add http-headers
     */
    public function send()
    {
       /* foreach ($this->_headers as $header => $type) {

        }*/

        return $this->_body;
    }
}