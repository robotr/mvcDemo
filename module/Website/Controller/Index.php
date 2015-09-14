<?php
/**
 * Index.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * Website\Controller\Index
 *
 * @package   Website\Controller
 * @category  module
 */

namespace Website\Controller;

use SlickFW\Mvc\Controller\ControllerAbstract;
use Website\Model\View\Container;
use Website\Setup;

class Index extends ControllerAbstract
{
    public function index()
    {
        /** @var Setup $setup */
        $setup = Setup::getInstance('Website');
        $viewInit = array(
            'title' => 'Startpage',
            'head1' => 'Welcome Guest!',
            'head2' => 'You are on the Startpage',
            'link1' => array('aText' => 'Stay in contact&hellip;', 'aHref' => 'contact'),
            'link2' => array('aText' => 'See the world&hellip;', 'aHref' => '?world=1')
        );
        if (Setup::getInstance('Website')->isDefaultModule()) {
            $viewInit['defaultModule'] = $setup->get('defaultmodule');
        }
        $this->_model = new Container($this->view, $viewInit);
        if ($this->_request->getQuery()->count() > 0
            && $this->_request->getQuery()->offsetExists('world')
            && $this->_request->getQuery()->offsetGet('world') == 1
        ) {
            $this->_model->getCountries();
        }
        $this->_model->assignViewVars();
    }

    public function contact()
    {
        $this->view->noLayout();
        if ($this->_request->isAjax()) {
            $this->view->text = 'Please enter your e-mail address to register for our daily newsletter&hellip;';
        }
    }

}
