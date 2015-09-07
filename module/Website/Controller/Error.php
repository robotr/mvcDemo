<?php
/**
 * Log.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * Website\Controller
 * @package Website\Controller
 */

namespace Website\Controller;

use SlickFW\Mvc\Controller\ControllerAbstract;
use Website\Model\View\Container;

class Error extends ControllerAbstract
{
    public function init()
    {
        $this->view->setDefaultPlaceholder('error');
        $this->view->setLayout(dirname(__DIR__) . '/View/scripts/Error/error');
        $viewInit = array('title' => 'Error');
        $this->_model = new Container($this->view, $viewInit);
    }

    public function noroute()
    {
        $this->_model->add(array('error' => '<h1>Application Error!</h1><b>Route not Found!</b>'));
        $this->_model->assignViewVars();
    }

    public function nocontroller()
    {
        $this->_model->add(array('error' => '<h1>Application Error!</h1><b>Controller not Found!</b>'));
        $this->_model->assignViewVars();
    }

    public function noaction()
    {
        $this->_model->add(array('error' => '<h1>Application Error!</h1><b>Action-method not Found!</b>'));
        $this->_model->assignViewVars();
    }

}
