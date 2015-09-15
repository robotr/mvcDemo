<?php
/**
 * PartAbstract.php
 */
/**
 * PartAbstract
 *
 * @package SlickFW\Mvc\View\Part
 */

namespace SlickFW\Mvc\View\Part;

use SlickFW\Mvc\View;

abstract class PartAbstract
{
    /**
     * @var View
     */
    protected $_view;

    /**
     * name of direct-call method fetched from the concrete view-part class
     * to use in __toString()-method
     * @var string
     */
    protected $_direct;

    /**
     * get a new view-part instance
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->_view = $view;
        $viewPart = explode('\\', get_class($this));
        $this->_direct = array_pop($viewPart);
        return $this;
    }

    /**
     * method to invoke direct method-call of View-Part on "to-string" event
     * @throws \Exception
     * @return string
     */
    public function __toString()
    {
        if (empty($this->_direct) || !method_exists($this, lcfirst($this->_direct))) {
            throw new \Exception('Method ' . lcfirst($this->_direct) . ' was not found in ' . get_class($this));
        }
        return $this->{lcfirst($this->_direct)}();
    }
}