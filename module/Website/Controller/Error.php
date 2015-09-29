<?php
/**
 * Log.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * Website\Controller\Error
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

    // todo - refactor (__call-method?)
    public function noroute()
    {
        $this->_model->add(array('messageH1' => 'Application Error!', 'messageB' => 'Route not Found!'));
        $this->_model->assignViewVars('error');
    }

    public function nocontroller()
    {
        $this->_model->add(array('messageH1' => 'Application Error!', 'messageB' => 'Controller not Found!'));
        $this->_model->assignViewVars('error');
    }

    public function noaction()
    {
        $this->_model->add(array('messageH1' => 'Application Error!', 'messageB' => 'Action-method not Found!'));
        $this->_model->assignViewVars('error');
    }

    public function internal()
    {
        $this->_model->add(array('messageH1' => 'Application Error!', 'messageB' => 'An internal error occurred!'));
        $this->_model->assignViewVars('error');
    }
}
