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
     * {@inheritdoc}
     */
    public function __construct(RequestAbstract $request)
    {
        parent::__construct($request);
        $this->setContentType('text/html; charset=UTF-8');
    }
}