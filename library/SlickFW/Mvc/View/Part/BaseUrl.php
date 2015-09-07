<?php
/**
 * BaseUrl.php
 */
/**
 * BaseUrl
 * @package SlickFW\Mvc\View\Part
 */

namespace SlickFW\Mvc\View\Part;

class BaseUrl
{
    /**
     * View-Part direct method-call
     * @return string
     */
    public function baseUrl()
    {
        $basePath = (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '/index.php') ?
            str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) : null;
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $basePath;
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