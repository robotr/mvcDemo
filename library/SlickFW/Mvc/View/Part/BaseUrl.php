<?php
/**
 * BaseUrl.php
 */
/**
 * BaseUrl
 * @package SlickFW\Mvc\View\Part
 */

namespace SlickFW\Mvc\View\Part;

class BaseUrl extends PartAbstract
{
    /**
     * View-Part direct method-call
     * @return string
     */
    public function baseUrl()
    {
        $basePath = (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '/index.php') ?
            str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) : null;
        $server   = (isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME'] : 'http';
        $server  .= '://' . $_SERVER['HTTP_HOST'];
        $baseURl  = $server;
        $baseURl .= (empty($basePath)) ? '/' : $basePath;
        return $baseURl;
    }

}