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
        $server = (isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME'] : 'http';
        $server .= '://' . $_SERVER['HTTP_HOST'];
        return $server . $basePath;
    }

    /**
     * method to invoke direct method-call of View-Part on "to-string" event
     * @return string
     */
    public function __toString()
    {
        return $this->baseUrl();
    }
}